<?php

require_once __DIR__ . '/../models/AnalysisModel.php';

class AnalysisController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new AnalyticsModel($conn);
    }

    // Dashboard analytics
    public function getTitlesCount($user_id){
        return $this->model->getTitlesCount($user_id);
    }

    public function viewSubscription($userId) {
        return $this->model->viewSubscription($userId);
    }

    public function getBookViews($user_id) {
        return $this->model->getBookViews($user_id);
    }

    public function getTopBooks($user_id) {
        return $this->model->getUserMostViewedBooks($user_id);
    }

    public function getProfileViews($user_id) {
        return $this->model->getProfileViews($user_id);
    }
    
    public function getServiceViews($user_id) {
        return $this->model->getServiceViews($user_id);
    }

    public function getEventViews($user_id) {
        return $this->model->getEventViews($user_id);
    }

    public function getDownloadsByEmail($email) {
        return $this->model->getDownloadsByEmail($email);
    }

    // NEW Monthly/Yearly grouped analytics

    public function getBookViewsByMonthYear($user_id)
    {
        return $this->model->getBookViewsByMonthYear($user_id);
    }

    public function getProfileViewsByMonthYear($user_id)
    {
        return $this->model->getProfileViewsByMonthYear($user_id);
    }

    public function getServiceViewsByMonthYear($user_id)
    {
        return $this->model->getServiceViewsByMonthYear($user_id);
    }

    public function getEventViewsByMonthYear($user_id)
    {
        return $this->model->getEventViewsByMonthYear($user_id);
    }

    // Geographic analytics for books
    public function getBookViewsByCountry($user_id)
    {
        return $this->model->getBookViewsByCountry($user_id);
    }

    public function getBookViewsByProvince($user_id, $country = null)
    {
        return $this->model->getBookViewsByProvince($user_id, $country);
    }

    public function getBookViewsByCity($user_id, $country = null, $province = null)
    {
        return $this->model->getBookViewsByCity($user_id, $country, $province);
    }
}
