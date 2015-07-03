<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 7/4/15
 * Time: 4:03 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * @param string $file
 * @param string $delimiter
 * @return array
 */
function load_data($file, $delimiter="\t") {

    $col_heads = array();
    $data = array();

    $row = 0;
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($row_data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

            if($row === 0) {
                $col_heads = $row_data;
            } else {
                $associative_row_data = array();
                foreach($row_data as $i => $value) {
                    // @todo handle duplicate/empty column name
                    $col_name = $col_heads[$i];
                    $associative_row_data[$col_name] = $value;
                }

                $data[$row] = $associative_row_data;
            }
            $row++;
        }
        fclose($handle);
    }

    return $data;
}

/**
 * @param array $data
 * @param string $sort_by
 * @param int $sort_order
 * @return bool
 */
function sort_data($data, $sort_by, $sort_order=SORT_ASC) {
    if(!count($data)) {
        return false;
    }

    if(is_null($sort_by)) {
        return $data;
    }

    $sortArray = array();

    foreach($data as $row_data){
        foreach($row_data as $key=>$value){
            if(!isset($sortArray[$key])){
                $sortArray[$key] = array();
            }
            $sortArray[$key][] = $value;
        }
    }

    array_multisort($sortArray[$sort_by],$sort_order,$data);

    return $data;
}