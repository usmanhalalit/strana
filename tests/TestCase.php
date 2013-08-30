<?php namespace Strana;

//use Mockery as m;
use Viocon\Container;

class TestCase extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
		
    }

    public function tearDown()
    {
        //m::close();
    }

    public function callbackMock()
    {
        $args = func_get_args();

        return count($args) == 1 ? $args[0] : $args;
    }
}
