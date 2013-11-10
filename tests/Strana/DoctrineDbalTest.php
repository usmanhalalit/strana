<?php namespace Strana;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineDbalTest extends TestCase
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    public function setUp()
    {
        parent::setUp();
        $config = new Configuration();

        $connectionParams = array(
            'dbname' => ':memory:',
            'driver' => 'pdo_sqlite',
        );
        $conn = DriverManager::getConnection($connectionParams, $config);
        $this->qb = $conn->createQueryBuilder();

        $sql = "CREATE TABLE sample(
                       t_key             TEXT     NOT NULL,
                       t_value           TEXT    NOT NULL
                    );";
        $conn->query($sql);

        for ($i = 1; $i <= 90; $i++) {
            $conn->query("INSERT INTO SAMPLE VALUES ('Key$i', 'Value$i')");
        }
    }

    public function testPaginationWithDoctrineDbalAdapter()
    {
        $cqb = clone($this->qb);
        $records = $this->qb->select('*')->from('sample', 'sample');

        $cqb->select('*')->from('sample', 'sample')->setMaxResults(20)->setFirstResult(60);
        $expected = $cqb->execute()->fetchAll();
        /*var_dump($this->qb->execute()->fetchAll());
        exit;*/
        $paginatorClass = new Paginator();
        $paginator = $paginatorClass->page(4)->perPage(20)->make($records);

        $this->assertEquals(90, $paginator->total(), 'Failed asserting pagination total.');
        $this->assertEquals($expected, $paginator->records(), 'Failed asserting pagination records.');
    }
}
