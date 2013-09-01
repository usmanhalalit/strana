<?php namespace Strana;

class LinkCreator {

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    public function __construct(ConfigHelper $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    public function createLinks($totalRecords)
    {
        $currentPage = $this->configHelper->getCurrentPage();
        $totalPages = $this->configHelper->getTotalPages($totalRecords);
        $pages = $this->getPages($totalRecords, $this->configHelper->getLimit(), $currentPage, $this->configHelper->getMaximumPages());

        $prevLiClass = '';
        $prevLinkHref = 'javascript:void(0)';
        if ($currentPage == 1) {
            $prevLiClass = 'disabled';
        } else {
            $prevLinkHref = '?page='.($currentPage - 1);
        }

        $nextLiClass = '';
        $nextLinkHref = 'javascript:void(0)';
        if ($currentPage == $totalPages) {
            $nextLiClass = 'disabled';
        } else {
            $nextLinkHref = '?page='.($currentPage + 1);
        }

        $output = '<ul class="pagination">';
        $output .= '<li class="' . $prevLiClass . '"><a class="prev" href="' . $prevLinkHref . '">&laquo;</a></li>';
        // TODO Append query string
        foreach($pages as $page) {
            $currentClass = $page == $currentPage ? 'active' : '';
            $output .= '<li class="' . $currentClass . '"><a href="?page=' . $page . '">' . $page . '</a></li>';
        }
        $output .= '<li class="' . $nextLiClass . '"><a class="next" href="' . $nextLinkHref . '">&raquo;</a></li>';
        $output .= '</ul>';

        return $output;
    }

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