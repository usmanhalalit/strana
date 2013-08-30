<?php
require 'vendor/autoload.php';
$connection = new \Pixie\Connection('sqlite', array('driver' => 'sqlite', 'database' => ':memory:'));
$qb = new \Pixie\QueryBuilder\QueryBuilderHandler($connection);

$qb->getConnection()->getEventHandler()->registerEvent('after-select', ':any', function($q)
{
    var_dump($q->getQuery()->getRawSql());
});

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
$paginatorClass = new \Strana\Paginator();

/*$config = array(
    'perPage' => 10,
    'page' => 2,
);*/
$paginator = $paginatorClass->perPage(10)->make($records, 'Pixie');

foreach ($paginator as $item) {
    var_dump($item);
}


echo '<br><br>' . $paginator;
