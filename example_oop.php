<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 7/4/15
 * Time: 3:46 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'CK_Process.class.php';

$obj = new CK_Process('testdata.tsv');
$obj->setSortBy('count');
$obj->setSortOrder(SORT_DESC);
$data = $obj->sortData();

print_r($data);
