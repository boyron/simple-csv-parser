<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 7/4/15
 * Time: 4:14 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'functions.php';

$data = sort_data(load_data('testdata.tsv'),'id', SORT_DESC);

print_r($data);
