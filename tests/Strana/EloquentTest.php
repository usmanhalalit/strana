<?php namespace Strana;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Collection;

class EloquentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $capsule = new Capsule;

        $capsule->addConnection(array(
                'driver'  => 'sqlite',
                'database'  => ':memory:',
            ));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::statement("CREATE TABLE sample(
                       t_key             TEXT     NOT NULL,
                       t_value           TEXT    NOT NULL
                    );");

        for ($i = 1; $i <= 100; $i++) {
            $record = array(
                't_key' => 'Key ' . $i,
                't_value' => 'Value ' . $i,
            );

            Capsule::table('sample')->insert($record);
        }
    }

    public function testPaginationWithEloquentAdapter()
    {
        $records = Capsule::table('sample');
        $expected = Capsule::table('sample')->limit(20)->offset(60)->get();
        //Check if expected record is an instance of Collection
        if($expected instanceof Collection){
            //Record will be saved as array, so convert expected to array
            $expected = $expected->toArray();
        }

        $paginatorClass = new Paginator();

        $paginator = $paginatorClass->page(4)->perPage(20)->make($records);        

        $this->assertEquals(100, $paginator->total(), 'Failed asserting pagination total.');
        $this->assertEquals($expected, $paginator->records(), 'Failed asserting pagination records.');
    }
}
