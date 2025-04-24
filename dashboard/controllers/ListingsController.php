<?php
require_once '../models/Listings.php';

class ListingsController {
  private $model;

  public function __construct($conn) {
    $this->model = new Listing($conn);
  }

  public function listBooks($userkey) {
    return $this->model->getUserBooks($userkey);
  }

  public function listEvents($userkey) {
    return $this->model->getUserEvents($userkey);
  }

   public function listServices($userkey) {
    return $this->model->getUserServices($userkey);
  }

   public function listReviews($userkey) {
    return $this->model->getUserReviews($userkey);
  }

  // for some reason i found - the query not taking a userkey/id parameter (very suss)
  public function listCustomers() {
    return $this->model->getUserCustomers();
  }
}
