<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PageVisitsController
{

    private $pageVisitsModel;

    public function __construct($conn)
    {
        $this->pageVisitsModel = new PageVisitsModel($conn);
    }
}
