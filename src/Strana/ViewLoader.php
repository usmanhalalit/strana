<?php namespace Strana;

use Strana\Exceptions\Exception;

class ViewLoader {
    /**
     * @param $file_path
     * @param array $data
     * @return mixed
     * @throws Exceptions\Exception
     *
     * Process view files.
     *
     * Credit: FuelPHP https://github.com/fuel/core/blob/1.8/develop/classes/view.php#L228
     */
    public function load($file_path, Array $data = array())
    {
        $file_path = dirname(__FILE__) . '/Views/' . $file_path;

        if (!is_readable($file_path) ) {
            throw new Exception('View not found ' . $file_path . '.');
        }

        $clean_room = function($__file_name, array $__data)
        {
            extract($__data, EXTR_REFS);

            // Capture the view output
            ob_start();

            try
            {
                // Load the view within the current scope
                require $__file_name;
            }
            catch (\Exception $e)
            {
                // Delete the output buffer
                ob_end_clean();

                // Re-throw the exception
                throw $e;
            }

            // Get the captured output and close the buffer
            return ob_get_clean();
        };
        return $clean_room($file_path, $data);
    }
}