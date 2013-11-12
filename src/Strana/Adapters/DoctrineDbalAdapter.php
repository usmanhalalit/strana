<?php namespace Strana\Adapters;

use Doctrine\DBAL\Query\QueryBuilder;
use Strana\ConfigHelper;
use Strana\Exceptions\InvalidArgumentException;
use Strana\Interfaces\CollectionAdapter;

class DoctrineDbalAdapter implements CollectionAdapter{

    /**
     * @var \Strana\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var QueryBuilder
     */
    protected $records;

    /**
     * @param $records
     * @param ConfigHelper $configHelper
     * @throws \Strana\Exceptions\InvalidArgumentException
     */
    public function __construct($records, ConfigHelper $configHelper)
    {
        if (! $records instanceof QueryBuilder) {
            throw new InvalidArgumentException('Expected Doctrine\DBAL\Query\QueryBuilder.');
        }

        $this->records = $records;
        $this->configHelper = $configHelper;
    }

    /**
     * @return mixed|array
     */
    public function slice()
    {
        $records = clone($this->records);
        $limit = $this->configHelper->getLimit();
        $offset = $this->configHelper->getOffset();
        return $records->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->execute()
                        ->fetchAll();
    }

    /**
     * @return mixed
     */
    public function total()
    {
        $records = clone($this->records);
        $records->select('count(*) as cnt');
        $count = $records->execute()->fetchColumn();
        return $count;
    }
}