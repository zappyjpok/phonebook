<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 8/11/2015
 * Time: 4:15 PM
 */

class pagination {

    /**
     * @var -- number of files per page
     */
    protected $filesPerPage;

    /**
     * @var -- database records to be paginated
     */
    protected $records;

    /**
     * @var -- final output of records per page
     */
    protected $recordsPerPage;

    /**
     * @var -- number of records
     */
    protected $numberOfRecords;

    /**
     * @var array -- array of output messages
     */
    protected $messages = [];

    /**
     * Set the records and the array records chunk
     *
     * @param $records
     * @param $filesPerPage
     */
    public function __construct($records, $filesPerPage)
    {
        $this->records = $records;
        $this->filesPerPage = $filesPerPage;
    }

    /**
     * Get Records
     */
    public function createPagination()
    {

        $this->splitRecords();
        $this->getRows();

        return $this->recordsPerPage;
    }

    public function getRows()
    {
        $this->numberOfRecords = count($this->records);
        return $this->numberOfRecords;
    }

    private function splitRecords()
    {
        $i = 0;
        // array chunk of array
        foreach(array_chunk($this->records, $this->filesPerPage) as $records)
        {
            $this->recordsPerPage[$i] = $records;
            $i++;
        }
    }





}