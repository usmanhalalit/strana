<?php namespace Strana\Adapters;

use Illuminate\Database\Query\Builder as EloquentQuery;
use Strana\ConfigHelper;
use Strana\Interfaces\CollectionAdapter;

class EloquentAdapter implements CollectionAdapter{

    /**
     * @var \Strana\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var EloquentQuery
     */
    protected $records;

    public function __construct($records, ConfigHelper $configHelper)
    {
        if (! $records instanceof EloquentQuery) {
            throw new \InvalidArgumentException;
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    public function slice()
    {
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return $this->records->limit($limit)->offset($offset)->get();
    }

    public function total()
    {
        return $this->records->count();
    }
}