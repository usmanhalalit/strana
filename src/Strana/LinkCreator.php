<?php namespace Strana;

class LinkCreator {

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var InfiniteScroll
     */
    protected $infiniteScroll;

    /**
     * @param ConfigHelper $configHelper
     * @param InfiniteScroll $infiniteScroll
     */
    public function __construct(ConfigHelper $configHelper, InfiniteScroll $infiniteScroll)
    {
        $this->configHelper = $configHelper;
        $this->infiniteScroll = $infiniteScroll;
    }

    /**
     * @param $totalRecords
     * @return string
     */
    public function createLinks($totalRecords)
    {
        $currentPage = $this->configHelper->getCurrentPage();
        $totalPages = $this->configHelper->getTotalPages($totalRecords);
        $pages = $this->getPages($totalRecords, $this->configHelper->getLimit(), $currentPage, $this->configHelper->getMaximumPages());

        $prevLiClass = 'prev';
        $prevLinkHref = 'javascript:void(0)';
        if ($currentPage == 1) {
            $prevLiClass = 'disabled';
        } else {
            $prevLinkHref = $this->buildQueryString($currentPage - 1);
        }

        $nextLiClass = 'next';
        $nextLinkHref = 'javascript:void(0)';
        if ($currentPage == $totalPages) {
            $nextLiClass = 'disabled';
        } else {
            $nextLinkHref = $this->buildQueryString($currentPage + 1);
        }

        $output = '<ul class="pagination">';
        $output .= '<li class="' . $prevLiClass . '"><a href="' . $prevLinkHref . '">&laquo;</a></li>';
        foreach($pages as $page) {
            $currentClass = $page == $currentPage ? 'active current' : '';
            $output .= '<li class="' . $currentClass . '"><a href="' . $this->buildQueryString($page) . '">' . $page . '</a></li>';
        }
        $output .= '<li class="' . $nextLiClass . '"><a href="' . $nextLinkHref . '">&raquo;</a></li>';
        $output .= '</ul>';

        return $this->addInfiniteScroll($output);
    }

    protected function buildQueryString($page)
    {
        $get = $_GET;
        $get['page'] = $page;
        $queryString = http_build_query($get);
        return $queryString = '?' . $queryString;
    }

    /**
     * @param $output
     * @return string
     */
    protected function addInfiniteScroll($output)
    {
        if (($config = $this->configHelper->getInfiniteScroll())  !== false) {
            $output = $output . $this->infiniteScroll->getJs($config);
        }

        return $output;
    }

    /**
     * @param $total
     * @param null $limit
     * @param null $current
     * @param null $adjacents
     * @return array
     *
     * Credit: http://stackoverflow.com/a/7562895/656489
     */
    protected function getPages($total, $limit = null, $current = null, $adjacents = null)
    {
        $result = array();

        if (isset($total, $limit) === true)
        {
            $result = range(1, ceil($total / $limit));

            if (isset($current, $adjacents) === true)
            {
                if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1)
                {
                    $result = array_slice($result, max(0, min(count($result) - $adjacents, intval($current) - ceil($adjacents / 2))), $adjacents);
                }
            }
        }

        return $result;
    }
}