<?php namespace Strana;

class ConfigHelper {
    /**
     * @var array
     */
    protected $config;

    public function __construct(Array $config)
    {
        $this->config = $config;
        $this->setDefaults();
    }

    protected function setDefaults()
    {
        if (!isset($this->config['perPage'])) {
            $this->config['perPage'] = 25;
        }

        if (!isset($this->config['page'])) {
            $this->config['page'] = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        }

        if (!isset($this->config['maximumPages'])) {
            $this->config['maximumPages'] = 5;
        }

        if (!isset($this->config['infiniteScroll'])) {
            $this->config['infiniteScroll'] = false;
        }
    }

    public function getCurrentPage()
    {
        return $this->config['page'];
    }

    public function getOffset()
    {
        return $this->config['perPage'] * ($this->config['page'] - 1);
    }

    public function getLimit()
    {
        return $this->config['perPage'];
    }

    public function getMaximumPages()
    {
        return $this->config['maximumPages'];
    }

    public function getTotalPages($totalRecords)
    {
        $pages = $totalRecords / $this->getLimit();
        // If we have decimal value like 2.2 then we need 3 pages, ceil it.
        $pages = ceil($pages);
        return $pages;
    }

}