<?php namespace Strana;

class ConfigHelper {
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(Array $config)
    {
        $this->config = $config;
        $this->setDefaults();
    }

    /**
     * Set default config values
     */
    protected function setDefaults()
    {
        if (!isset($this->config['perPage'])) {
            $this->config['perPage'] = 25;
        }

        if (!isset($this->config['page'])) {
            $get = $_GET;
            $this->config['page'] = isset($get['page']) ? (int) $get['page'] : 1;
        }

        if (!isset($this->config['maximumPages'])) {
            $this->config['maximumPages'] = 5;
        }

        if (!isset($this->config['infiniteScroll'])) {
            $this->config['infiniteScroll'] = false;
        }
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->config['page'];
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->config['perPage'] * ($this->config['page'] - 1);
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->config['perPage'];
    }

    /**
     * @return mixed
     */
    public function getMaximumPages()
    {
        return $this->config['maximumPages'];
    }

    /**
     * @return mixed
     */
    public function getInfiniteScroll()
    {
        return $this->config['infiniteScroll'];
    }

    /**
     * @param $totalRecords
     * @return float
     */
    public function getTotalPages($totalRecords)
    {
        $pages = $totalRecords / $this->getLimit();
        // If we have decimal value like 2.2 then we need 3 pages, ceil it.
        $pages = ceil($pages);
        return $pages;
    }

}