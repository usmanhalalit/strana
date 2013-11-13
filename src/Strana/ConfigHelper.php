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
        $get = $_GET;
        $page = isset($get['page']) ? (int) $get['page'] : 1;
        $defaults = array(
            'perPage'           =>  20,
            'page'              =>  $page,
            'maximumPages'      =>  5,
            'infiniteScroll'    =>  false,
        );

        $this->config = array_merge($defaults, $this->config);
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