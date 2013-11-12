<?php namespace Strana;

use Pixie\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;

class PixieTest extends TestCase
{
    /**
     * @var QueryBuilderHandler
     */
    protected $qb;

    public function setUp()
    {
        parent::setUp();
        $connection = new Connection('sqlite', array('driver' => 'sqlite', 'database' => ':memory:'));
        $this->qb = new QueryBuilderHandler($connection);

        $this->qb->query("CREATE TABLE sample(
                       t_key             TEXT     NOT NULL,
                       t_value           TEXT    NOT NULL
                    );");

        for ($i = 1; $i <= 100; $i++) {
            $record = array(
                't_key' => 'Key ' . $i,
                't_value' => 'Value ' . $i,
            );

            $this->qb->table('sample')->insert($record);
        }
    }

    public function testPaginatorGenerateWithPixieAdapter()
    {
        $records = $this->qb->table('sample')->select('t_value');
        $expected = $this->qb->table('sample')->select('t_value')->limit(10)->offset(10)->get();
        $paginatorClass = new Paginator();

        $paginator = $paginatorClass->page(2)->perPage(10)->make($records);

        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
        $this->assertEquals($expected, $paginator->records(), 'Failed asserting pagination records.');

    }
}
