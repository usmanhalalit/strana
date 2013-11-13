<?php namespace Strana;

class PaginatorTest extends TestCase
{

    public function testPaginatorGenerateWithArrayAdapterAndWithDefaults()
    {
        $paginatorClass = new Paginator();
        $records = array();
        for ($i = 1; $i <= 100; $i++) {
            $records['Key ' . $i] = 'Value ' . $i;
        }

        $paginator = $paginatorClass->make($records, 'Array');

        $expected = array();
        for ($i = 1; $i <= 20; $i++) {
            $expected['Key ' . $i] = 'Value ' . $i;
        }

        $this->assertSame($expected, $paginator->records(), 'Failed asserting pagination records.');
        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
    }

    public function testPaginatorGenerateWithArrayAdapter()
    {
        $paginatorClass = new Paginator();
        $records = array();
        for ($i = 1; $i <= 100; $i++) {
            $records['Key ' . $i] = 'Value ' . $i;
        }

        $config = array(
            'perPage' => 10,
            'page' => 2,
        );
        $iasConfig = array('loaderDelay' => 800);
        $paginator = $paginatorClass->infiniteScroll($iasConfig)->make($records, 'Array', $config);

        $expected = array();
        for ($i = 11; $i <= 20; $i++) {
            $expected['Key ' . $i] = 'Value ' . $i;
        }

        $this->assertSame($expected, $paginator->records(), 'Failed asserting pagination records.');
        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
    }

    public function testHTMLOutput()
    {
        $paginatorClass = new Paginator();
        $records = array();
        for ($i = 1; $i <= 100; $i++) {
            $records['Key ' . $i] = 'Value ' . $i;
        }

        $paginator = $paginatorClass->page(4)->perPage(10)->make($records, 'Array');

        // Cover foreach, iterator
        foreach($paginator as $item) {

        }

        $expected = '<ul class="pagination"><li class="prev"><a href="?page=3">&laquo;</a></li><li class=""><a href="?page=2">2</a></li><li class=""><a href="?page=3">3</a></li><li class="active current"><a href="?page=4">4</a></li><li class=""><a href="?page=5">5</a></li><li class=""><a href="?page=6">6</a></li><li class="next"><a href="?page=5">&raquo;</a></li></ul>';
        $this->assertEquals($expected, (string)$paginator);
    }

    /**
     * @expectedException Strana\Exceptions\InvalidArgumentException
     */
    public function testAdapterNotFoundException()
    {
        $paginatorClass = new Paginator();
        $paginatorClass->make(array(), 'Foo');
    }

    /**
     * @expectedException Strana\Exceptions\InvalidArgumentException
     */
    public function testWithInvalidAdapter()
    {
        $paginatorClass = new Paginator();
        $paginatorClass->make(array(), new \stdClass());
    }
}