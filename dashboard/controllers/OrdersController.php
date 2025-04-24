<?php
require_once '../models/listings.php';

class OrdersController {
    private $model;

    public $netIncome;
    public $transactions;
    public $totalCustomers;
    public $pendingOrders;
    public $invoices;

    public function __construct($con) {
        $this->model = new Listing($con);
        $this->initData();
    }

    private function initData() {
        $this->netIncome = $this->model->getNetIncome();
        $this->transactions = $this->model->getTransactionsCount();
        $this->totalCustomers = $this->model->getTotalCustomers();
        $this->pendingOrders = $this->model->getPendingOrders();
        $this->invoices = $this->model->getAllInvoices();
    }
}
