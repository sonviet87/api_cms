<?php
namespace App\Repositories;
use App\Interfaces\DashboardInterface;
use App\Models\KpiCustomer;


class DashboardRepository implements DashboardInterface {
    protected $model;
    function __construct(KpiCustomer $kpiCustomer){
        $this->model = $kpiCustomer;
    }

    public function getList($filter=[]){
        $query = $this->model;
        return $query->orderBy('id', 'desc')->get();
    }






}
