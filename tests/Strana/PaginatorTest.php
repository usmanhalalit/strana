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
        for ($i = 1; $i <= 25; $i++) {
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
        $paginator = $paginatorClass->make($records, 'Array', $config);

        $expected = array();
        for ($i = 11; $i <= 20; $i++) {
            $expected['Key ' . $i] = 'Value ' . $i;
        }

        $this->assertSame($expected, $paginator->records(), 'Failed asserting pagination records.');
        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
    }

    public function testPaginatorGenerateWithPixieAdapter()
    {
        $connection = new \Pixie\Connection('sqlite', array('driver' => 'sqlite', 'database' => ':memory:'));
        $qb = new \Pixie\QueryBuilder\QueryBuilderHandler($connection);

        $qb->query("CREATE TABLE sample(
                       t_key             TEXT     NOT NULL,
                       t_value           TEXT    NOT NULL
                    );");

        for ($i = 1; $i <= 100; $i++) {
            $record = array(
                't_key' => 'Key ' . $i,
                't_value' => 'Value ' . $i,
            );

            $qb->table('sample')->insert($record);
        }

        $records = $qb->table('sample')->select('t_value');
        $expected = $qb->table('sample')->select('t_value')->limit(10)->offset(10)->get();
        $paginatorClass = new Paginator();

        $paginator = $paginatorClass->page(2)->perPage(10)->make($records, 'Pixie');

        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
        $this->assertEquals($expected, $paginator->records(), 'Failed asserting pagination records.');

    }

    public function testHTMLOutput()
    {
        $paginatorClass = new Paginator();
        $records = array();
        for ($i = 1; $i <= 100; $i++) {
            $records['Key ' . $i] = 'Value ' . $i;
        }

        $paginator = $paginatorClass->page(4)->perPage(10)->make($records, 'Array');
        $expected = '<ul class="pagination"><li><a class="" href="?page=3">&laquo;</a></li><li class=""><a href="?page=2">2</a></li><li class=""><a href="?page=3">3</a></li><li class="active"><a href="?page=4">4</a></li><li class=""><a href="?page=5">5</a></li><li class=""><a href="?page=6">6</a></li><li><a class="" href="?page=5">&raquo;</a></li></ul>';
        $this->assertEquals($expected, (string)$paginator);
    }
}