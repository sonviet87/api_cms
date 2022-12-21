<?php
namespace App\Repositories;

use App\Interfaces\ReportInterface;
use App\Models\FP;

class ReportRepository implements ReportInterface {
    protected $model;
    function __construct(FP $fp){
        $this->model = $fp;
    }


    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }
            if (isset($filter['type_fp']) && $filter['type_fp'] != '') {
                $query = $query->where('status', $filter['type_fp']) ;
            }
            if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
                $statDay = date('Y-m-d', strtotime($filter['startDay']));
                $endDay = date('Y-m-d', strtotime($filter['endDay']));
                $query = $query->whereDate('created_at','>=' ,$statDay)->whereDate('created_at','<=' ,$endDay);
            }
        }
       // dd($query->toSql() );
        return  $query->with(['user','account','contact'])->orderBy('created_at', 'desc')->paginate($perPage);
    }


}
