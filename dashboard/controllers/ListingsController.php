<?php
require_once '../models/Listings.php';

class ListingsController {
  private $model;
  public $netIncome;
  public $transactions;
  public $totalCustomers;
  public $pendingOrders;
  public $invoices;

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

  private function initData() {
        $this->netIncome = $this->model->getNetIncome();
        $this->transactions = $this->model->getTransactionsCount();
        $this->totalCustomers = $this->model->getTotalCustomers();
        $this->pendingOrders = $this->model->getPendingOrders();
        $this->invoices = $this->model->getAllInvoices();
    }
}
