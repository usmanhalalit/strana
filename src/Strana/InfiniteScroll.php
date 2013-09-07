<?php namespace Strana;

class InfiniteScroll {

    /**
     * @var ViewLoader
     */
    protected $viewLoader;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    public function __construct(ViewLoader $viewLoader, ConfigHelper $configHelper)
    {
        $this->viewLoader = $viewLoader;
        $this->configHelper = $configHelper;
    }

    public function getJs(Array $config = array())
    {
        $default = array(
            'container'             =>  '#container',
            'item'                  =>  '.item',
            'pagination'            =>  '.pagination',
            'next'                  =>  '.next a',
            'loader'                =>  'Loading ...',
            'triggerPageThreshold'  =>  5,
        );

        $data['config'] = array_merge($default, $config);

        return $this->viewLoader->load('infiniteScroll.php', $data);
    }
}