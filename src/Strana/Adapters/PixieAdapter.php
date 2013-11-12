<?php namespace Strana\Adapters;

use Pixie\QueryBuilder\QueryBuilderHandler;
use Strana\ConfigHelper;
use Strana\Exceptions\InvalidArgumentException;
use Strana\Interfaces\CollectionAdapter;

class PixieAdapter implements CollectionAdapter{

    /**
     * @var \Strana\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var \Pixie\QueryBuilder\QueryBuilderHandler
     */
    protected $records;

    /**
     * @param $records
     * @param ConfigHelper $configHelper
     * @throws \Strana\Exceptions\InvalidArgumentException
     */
    public function __construct($records, ConfigHelper $configHelper)
    {
        if (!$records instanceof QueryBuilderHandler) {
            throw new InvalidArgumentException('Expected Pixie\QueryBuilder\QueryBuilderHandler');
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    /**
     * @return null|\stdClass
     */
    public function slice()
    {
        $records = clone($this->records);
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return $records->limit($limit)->offset($offset)->get();
    }

    /**
     * @return int
     */
    public function total()
    {
        $records = clone($this->records);
        return $records->count();
    }
}