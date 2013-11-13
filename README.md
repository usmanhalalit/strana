# Strana
### Smart Pagination Library for PHP

[![Build Status](https://travis-ci.org/usmanhalalit/strana.png?branch=master)](https://travis-ci.org/usmanhalalit/strana) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/49091284-bd4e-455d-b821-ad6b33d25d37/small.png)](https://insight.sensiolabs.com/projects/49091284-bd4e-455d-b821-ad6b33d25d37 "This project passes all Insight checks successfully. This is very rare, and is worthy of the Platinum medal. Congratulations to all the contributors to this project for such a high quality.") [![Scrutinizer Code Quality Score](https://scrutinizer-ci.com/g/usmanhalalit/strana/badges/quality-score.png?s=41d13b5c7a983d1ed3637998d599e8e020e87538)](https://scrutinizer-ci.com/g/usmanhalalit/strana/) [![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/usmanhalalit/strana/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
___

A framework agnostic, smart pagination library for PHP. Just a few lines of code and fully functional pagination is ready.

Paginate your records with Strana. Strana will slice(limit and offset) these records, generate pagination links for you and reads page number from them, all automatically.

#### Features:

- Built-in adapters for [Doctrine](http://www.doctrine-project.org/projects/dbal.html), [Eloquent (Laravel)](https://github.com/illuminate/database), [Pixie](https://github.com/usmanhalalit/pixie), PHP Array and you can do it manually.
- Readable syntax
- Add Infinite Scroll with one line
- It automatically detects which DBAL you are using.
- Styles automatically with Twitter Bootstrap, Zurb Foundation and most of other CSS frameworks.


#### Screenshot:

![Screenshot](https://dl.dropboxusercontent.com/u/15827461/strana-sample.png)

## Example
Basically Strana makes it very easy, like the code below:
```PHP
$paginator = $strana->perPage(10)->make($records);
```
**That's basically it.**

### Full Usage Example
```PHP
// Make sure you have Composer's autoload file included
require 'vendor/autoload.php';

$strana = new \Strana\Paginator();
$records = array(1, 2, 3, .... 100);
$paginator = $strana->perPage(10)->make($records);

// Loop paginated items
foreach ($paginator as $item) {
    echo $item['field_name'] . '<br>';
}

// Print pagination links
echo '<br><br>' . $paginator;
```


There are some advanced options which are documented below. Sold? Lets install.

## Installation

Strana uses [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) to make things easy, its a requirement.

Learn to use composer and add this to require section (in your composer.json):

    "usmanhalalit/strana": "1.*@dev"

And run:

    composer update

Library on [Packagist](https://packagist.org/packages/usmanhalalit/strana).



## Full Walkthrough

Strana has built in adapters for Doctrine Dbal, Laravel Eloquent, Pixie and PHP native arrays. If you want to paginate from any of these then you're in luck. More adapters will come according to feedback.

### Step 1:
Prepare your database or array records:

**Doctrine Dbal Example:**

```PHP
$records = $qb->select('*')->from('sample', 'sample');
```
**Laravel Eloquent (or Query Builder) Example:**

```PHP
$records = Capsule::select('*')->from('sample');
```
**Pixie Example:**

```PHP
$records = QB::select('*')->from('sample');
```
**Array Example:**

```PHP
$records = array(1, 2, 3, 4, 5);
```

### Step 2:
Paginate your records with Strana. Strana will slice(limit and offset) these records, generate pagination links for you and reads page number from them, all automatically.

```PHP
$strana = new \Strana\Paginator();
$paginator = $strana->perPage(10)->make($records);
```

### Step 3:
Loop paginated records to display:

```PHP
foreach ($paginator as $item) {
    echo $item['field'] . '<br>';
}
```

### Step 4:
Print your pagination links:
```PHP
echo $paginator;
```

It will produce something like this:

![Screenshot](https://dl.dropboxusercontent.com/u/15827461/strana-sample.png)


## Infinite Scroll
Strana comes with out of the box Infinite Scrolling, enable it with just one method. 

```PHP
$strana->infiniteScroll()->perPage(10)->make($records);
```
And then wrap all your pagination items and pagination links with certain CSS classes, like the example below.

#### Example

```PHP
echo '<div class="container">';
foreach ($paginator as $item) {
    print('<div class="item">' . $item['t_value'] . '</div>');
}


echo '<br><br>' . $paginator;
echo '</div>';
```

**That's it**, you're done with infinite scrolling.
___

Strana uses the awesome [Infinite Ajax Scroll](https://github.com/webcreate/Infinite-Ajax-Scroll) jQuery plugin. All config options supported by this plugin can be passed with Strana.

```PHP
$iasConfig = array(
    'loaderDelay' => 600,
    'loader'      => '<img src="images/loader.gif"/>',    
);

$strana->infiniteScroll($iasConfig)->perPage(10)->make($records);
```

Cool, yeah?

## Usage API
#### perPage($perPage)
Number of items on per page, default 10.


#### page($page)
Which page to show, default is read from query string else 1.


#### infiniteScroll(Array $config = array())
Enable Infinite Scrolling using Ajax. Options can be passed using `$config`.

#### make($records, $adapter = null, $config = array())
Make and return paginator object.

`$records` = records to be paginated.

`$adapter` = which adapter you want to use as string, `DoctrineDbal`, `Eloquent`, `Pixie`, `Array` and so. If omitted or a falsy value is given then Strana is smart enough to detect the adapter itself.
If you want to use your own(custom) adapter then pass the object/instance here. Your custom adapter must implement ` Strana\Interfaces\CollectionAdapter` interface.

`$config` = all Strana config can be passed here too, as an array. like `$config = array('maximumPages' => 7)`

## The Recordset Object

`make()` method returns an instance of `Strana\RecordSet` class. Its a **polymorphic** object, for example:
```PHP
$paginator = $strana->make($records);
```
Here, if you loop `$paginator` with `foreach` then it will work like an array and iterate through paginated items, if you `echo` `$paginator` it will work like a string and print the pagination links. And of course you can use like a class object.
`$paginator->records()` will return paginated records.
`$paginator->total()` will return total records count.
`$paginator->links()` will return pagination links.


## Developing Your Own Adapter

If you are not using the database tool whose adapter ships with Strana then you can build your own adapter with ease. Create your class which must implement `Strana\Interfaces\CollectionAdapter`.

Example adapter, please read comments to understand.


```PHP
<?php
use Strana\ConfigHelper;
use Strana\Interfaces\CollectionAdapter;

class CustomAdapter implements CollectionAdapter{

    /**
     * @var \Strana\ConfigHelper
     * Config helper is a helper class, which gives you config values
     *  used by Strana.
     */
    protected $configHelper;

    /**
     * @var
     */
    protected $records;

    public function __construct($records, ConfigHelper $configHelper)
    {
        $this->records = $records;
        $this->configHelper = $configHelper;
    }
    
    /**
     * This method should limit and offset your records and return.
     */
    public function slice()
    {
        // Here you will get the database object passed to Strana.
        //  Clone it.
        $records = clone($this->records);
        
        // Get the limit number from Strana config
        $limit = $this->configHelper->getLimit();
        
        // Get the offset number from Strana config
        $offset = $this->configHelper->getOffset();
        
        // Limit your records
        $records->limit($limit);
        // Offset your records
        $records->offset($offset);
        
        // Return your sliced records
        return $records->get();
    }

    /**
     * This method should return total count of all of your records.
     */
    public function total()
    {
        // Here you will get the database object passed to Strana.
        //  Clone it.
        $records = clone($this->records);
        
        // Return your total records count, unsliced.
        return $records->count();
    }
}
```

Please also look at the `ArrayAdapter` to get more idea.

#### Using Your Adapter


```PHP
$strana = new \Strana\Paginator();
$configHelper = new \Strana\ConfigHelper($strana->getConfig());
$adapter = new CustomAdapter($yourRecords, $configHelper);
$paginator = $paginatorClass->make($yourRecords, $adapter);
```

___
&copy; 2013 [Muhammad Usman](http://usman.it/). Licensed under MIT license.