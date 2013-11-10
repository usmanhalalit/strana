<?php namespace Strana\Adapters;

use Strana\ConfigHelper;
use Strana\Interfaces\CollectionAdapter;

class ArrayAdapter implements CollectionAdapter{

    /**
     * @var \Strana\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var array
     */
    protected $records;

    public function __construct($records, ConfigHelper $configHelper)
    {
        if (!is_array($records)) {
            throw new \InvalidArgumentException;
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    public function slice()
    {
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return array_slice($this->records, $offset, $limit);
    }

    public function total()
    {
        return count($this->records);
    }
}