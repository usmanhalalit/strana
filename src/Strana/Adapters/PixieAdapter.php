<?php namespace Strana\Adapters;

use Pixie\QueryBuilder\QueryBuilderHandler;
use Strana\ConfigHelper;
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

    public function __construct($records, ConfigHelper $configHelper)
    {
        if (! $records instanceof QueryBuilderHandler) {
            throw new \InvalidArgumentException;
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    public function slice()
    {
        $records = clone($this->records);
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return $records->limit($limit)->offset($offset)->get();
    }

    public function total()
    {
        $records = clone($this->records);
        return $records->count();
    }
}