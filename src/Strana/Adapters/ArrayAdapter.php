<?php namespace Strana\Adapters;

use Strana\ConfigHelper;
use Strana\Exceptions\InvalidArgumentException;
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

    /**
     * @param $records
     * @param ConfigHelper $configHelper
     * @throws \Strana\Exceptions\InvalidArgumentException
     */
    public function __construct($records, ConfigHelper $configHelper)
    {
        if (!is_array($records)) {
            throw new InvalidArgumentException('ArrayAdapter expects array as records.');
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    /**
     * @return array
     */
    public function slice()
    {
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return array_slice($this->records, $offset, $limit);
    }

    /**
     * @return int
     */
    public function total()
    {
        return count($this->records);
    }
}