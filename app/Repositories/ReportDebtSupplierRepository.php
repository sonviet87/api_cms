<?php
namespace App\Repositories;

use App\Interfaces\ReportDebtSupplierInterface;
use App\Models\DebtSupplier;

class ReportDebtSupplierRepository implements ReportDebtSupplierInterface {
    protected $model;
    function __construct(DebtSupplier $debt){
        $this->model = $debt;
    }


    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;

        if(!empty($filter)) {
            if (isset($filter['fp_id']) && $filter['fp_id'] != '') {
                $query = $query->where('fp_id', $filter['fp_id']) ;

            }
            if (isset($filter['isDone']) && $filter['isDone'] != '') {
                $query = $query->where('isDone', $filter['isDone']) ;

            }
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $user_id = $filter['user_id'];
                $query = $query->whereHas('fp', function ($query) use ( $user_id){
                    $query->where('user_assign',  $user_id);
                }) ;

            }
            if (isset($filter['account_id']) && $filter['account_id'] != '') {
                $account_id = $filter['account_id'];
                $query = $query->whereHas('fp', function ($query) use ( $account_id){
                    $query->where('account_id',  $account_id);
                }) ;

            }

            if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
                $statDay = date('Y-m-d H:i:s',strtotime($filter['startDay']));
                $endDay = date('Y-m-d H:i:s', strtotime($filter['endDay']));
                $query = $query->whereDate('created_at','>=' ,$statDay)->whereDate('created_at','<=' ,$endDay);
            }
        }
        //dd($query->toSql() );
        return  $query->with(['fp'])->orderBy('created_at', 'desc')->paginate($perPage);
    }


}
