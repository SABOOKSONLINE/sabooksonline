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
    public function getTitlesCount($user_id)
    {
        return $this->model->getTitlesCount($user_id);
    }
}
