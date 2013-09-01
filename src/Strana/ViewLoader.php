<?php namespace Strana;

use Strana\Exceptions\Exception;

class ViewLoader {
    public function load($path, Array $data = array())
    {
        $path = dirname(__FILE__) . '/Views/' . $path;

        if (!is_readable($path) ) {
            throw new Exception('View not found ' . $path . '.');
        }

        $viewData = file_get_contents($path);

        ob_start() && extract($data, EXTR_SKIP);
        eval('?>'.$viewData);
        $viewData = ob_get_clean();
        ob_flush();

        return $viewData;
    }
}