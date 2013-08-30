<?php namespace Strana;

class Paginator
{
    /**
     * @var array
     */
    protected $config = array();

    public function __construct()
    {

    }

    public function make($records, $adapter = 'Array', $config = array())
    {
        $adapter = '\\Strana\\Adapters\\' . $adapter . 'Adapter';
        $config = array_merge($this->getConfig(), $config);
        $this->setConfig($config);
        $configHelper = new ConfigHelper($this->getConfig());

        $recordSet = $this->generate($records, $adapter, $configHelper);

        $linkCreator = new LinkCreator($configHelper);
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
        if (!class_exists($adapter)) {
            throw new \InvalidArgumentException('Adapter not found ' . $adapter . '.' );
        }

        $adapterInstance = new $adapter($records, $configHelper);
        $total = $adapterInstance->total();
        $slicedRecords = $adapterInstance->slice();
        $recordSet = new RecordSet($slicedRecords, $total);

        return $recordSet;
    }
}
