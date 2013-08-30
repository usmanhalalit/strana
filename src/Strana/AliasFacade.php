<?php namespace Strana;

/**
 * This class gives the ability to access non-static methods statically
 *
 * Class AliasFacade
 *
 * @package Strana
 */
class AliasFacade
{

    /**
     * @var
     */
    protected static $stranaInstance;

    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (!static::$stranaInstance) {
            static::$stranaInstance = new Session();
        }

        // Call the non-static method from the class instance
        return call_user_func_array(array(static::$stranaInstance, $method), $args);
    }

    /**
     * @param QueryBuilderHandler $queryBuilderInstance
     */
    public static function setStranaInstance($queryBuilderInstance)
    {
        static::$stranaInstance = $queryBuilderInstance;
    }
}