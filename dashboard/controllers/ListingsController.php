<?php
require_once '../models/Listings.php';

class BookController {
  private $model;

  public function __construct($conn) {
    $this->model = new Listing($conn);
  }

  public function listBooks($userkey) {
    return $this->model->getUserBooks($userkey);
  }
}
