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
        return $records->limit($this->configHelper->getLimit())
                        ->offset($this->configHelper->getOffset())->get();
    }

    public function total()
    {
        // TODO cache count
        $records = clone($this->records);
        return $records->count();
    }
}