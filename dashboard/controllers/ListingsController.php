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
  public function getAccounts($userKey) {
    return $this->model->getAccountsByUserKey($userKey);
  }

  public function getViewToInclude($userKey, $subscription) {
    $accounts = $this->getAccounts($userKey);
    $firstAccount = $accounts[0] ?? null;
    $hasDomain = $firstAccount !== null;
    $isSabooks = $hasDomain && strpos($firstAccount['DOMAIN'], 'sabooksonline.co.za') !== false;

    if ($subscription === 'Free') {
        return 'includes/backend/screens/subdomain-creation.php';
    }

    if ($hasDomain) {
        return $isSabooks
            ? 'includes/backend/screens/register-domain.php'
            : 'includes/backend/screens/email-creation.php';
    }

    return 'includes/backend/screens/register-domain.php';
  }
}
