<?php namespace Strana\Interfaces;

use Strana\ConfigHelper;

interface CollectionAdapter {
    public function __construct($records, ConfigHelper $configHelper);
    public function slice();
    public function total();
}