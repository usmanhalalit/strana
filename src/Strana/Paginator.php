<?php namespace Strana;

use Strana\Exceptions\InvalidArgumentException;
use Pixie\QueryBuilder\QueryBuilderHandler as PixieQB;
use Doctrine\DBAL\Query\QueryBuilder as DoctrineDbalQB;
use Illuminate\Database\Query\Builder as EloquentQB;

class Paginator
{
    /**
     * @var array
     */
    protected $config = array();

    public function __construct()
    {

    }

    public function make($records, $adapter = null, $config = array())
    {
        $config = array_merge($this->getConfig(), $config);
        $this->setConfig($config);
        $configHelper = new ConfigHelper($this->getConfig());

        $recordSet = $this->generate($records, $adapter, $configHelper);
        $infiniteScroll = new InfiniteScroll(new ViewLoader(), $configHelper);

        $linkCreator = new LinkCreator($configHelper, $infiniteScroll);
        $links = $linkCreator->createLinks($recordSet->total());
        $recordSet->setLinks($links);
        return $recordSet;
    }

    public function page($currentPage)
    {
        $this->config['page'] = $currentPage;
        return $this;
    }

    public function perPage($perPage)
    {
        $this->config['perPage'] = $perPage;
        return $this;
    }

    public function infiniteScroll(Array $config = array())
    {
        $this->config['infiniteScroll'] = $config;
        return $this;
    }

    public function setConfig(Array $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function generate($records, $adapter, ConfigHelper $configHelper)
    {
        if (!$adapter || !is_string($adapter)) {
            // Auto detect
            $adapter = $this->detectAdapter($records);
        }

        $adapter = '\\Strana\\Adapters\\' . $adapter . 'Adapter';

        if (!class_exists($adapter)) {
            throw new InvalidArgumentException('Adapter not found ' . $adapter . '.' );
        }

        $adapterInstance = new $adapter($records, $configHelper);
        $total = $adapterInstance->total();
        $slicedRecords = $adapterInstance->slice();
        $recordSet = new RecordSet($slicedRecords, $total);

        return $recordSet;
    }

    protected function detectAdapter($records)
    {
        if (is_array($records)) {
            return 'Array';
        } elseif($records instanceof PixieQB) {
            return 'Pixie';
        } elseif($records instanceof DoctrineDbalQB) {
            return 'DoctrineDbal';
        } elseif($records instanceof EloquentQB) {
            return 'Eloquent';
        } else {
            return 'Null';
        }
    }
}
