<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 7/4/15
 * Time: 12:40 AM
 * To change this template use File | Settings | File Templates.
 */

class CK_Process {

    /**
     * @var string
     */
    private $file=null;

    /**
     * @var string
     */
    private $delimiter="\t";

    /**
     * 2 dimensional array containing file data
     * @var array
     */
    private $data=array();

    /**
     * @var array
     */
    private $col_heads=array();

    /**
     * @var string
     */
    private $sort_by=null;

    /**
     * @var int
     */
    private $sort_order=SORT_ASC;

    /**
     * @var int
     */
    private $sort_type=SORT_REGULAR;

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $sort_by
     */
    public function setSortBy($sort_by)
    {
        $this->sort_by = $sort_by;
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sort_by;
    }

    /**
     * @param int $sort_order SORT_ASC/SORT_DESC
     */
    public function setSortOrder($sort_order)
    {
        $this->sort_order = $sort_order;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sort_order;
    }

    /**
     * @param int $sort_type SORT_NUMERIC/SORT_STRING etc
     */
    public function setSortType($sort_type)
    {
        $this->sort_type = $sort_type;
    }

    /**
     * @return int
     */
    public function getSortType()
    {
        return $this->sort_type;
    }

    /**
     * @throws Exception
     */
    protected function loadData() {
        if(is_null($this->getFile())) {
            throw Exception('file not loaded');
        }

        $row = 0;
        if (($handle = fopen($this->getFile(), "r")) !== FALSE) {
            while (($row_data = fgetcsv($handle, 1000, $this->getDelimiter())) !== FALSE) {

                if($row === 0) {
                    $this->col_heads = $row_data;
                } else {
                    $associative_row_data = array();
                    foreach($row_data as $i => $value) {
                        // @todo handle duplicate/empty column name
                        $col_name = $this->col_heads[$i];
                        $associative_row_data[$col_name] = $value;
                    }

                    $this->data[$row] = $associative_row_data;
                }
                $row++;
            }
            fclose($handle);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function sortData() {
        if(!count($this->data)) {
            throw Exception('data not loaded');
        }

        if(is_null($this->getSortBy())) {
            return $this->data;
        }

        $sortArray = array();

        foreach($this->data as $row_data){
            foreach($row_data as $key=>$value){
                if(!isset($sortArray[$key])){
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }

        // @todo handle column type numeric/string
        // @update - works fine so far
        array_multisort($sortArray[$this->getSortBy()],$this->getSortOrder(),$this->data);

        return $this->data;
    }


    /**
     * @param $file
     */
    public function __construct ($file) {

        $this->setFile($file);
        $this->loadData();
    }

    public function  __destruct() {
        unset($this->data);
    }
}