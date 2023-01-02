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
                $query = $query->where('user_assign', $filter['user_id']) ;
               
            }
            if (isset($filter['account_id']) && $filter['account_id'] != '') {
                $query = $query->where('account_id', $filter['account_id']) ;
               
            }
            if (isset($filter['type_fp']) && $filter['type_fp'] != '') {
                $query = $query->where('status', $filter['type_fp']) ;
               
            }
            if (isset($filter['category_id']) && $filter['category_id'] != '') {
                $category_id = $filter['category_id'];
                $query = $query->WhereHas('fp_details', function ($query) use ( $category_id){
                    $query->where('category_id', $category_id);
                }) ;
            }
            if (isset($filter['supplier_id']) && $filter['supplier_id'] != '') {
                $supplier_id = $filter['supplier_id'];
                $query = $query->WhereHas('fp_details', function ($query) use ( $supplier_id){
                    $query->where('supplier_id', $supplier_id);
                }) ;
            }
            if (isset($filter['type_fp']) && $filter['type_fp'] != '') {
                $query = $query->where('status', $filter['type_fp']) ;
            }
            if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
                $statDay = date('Y-m-d H:i:s',strtotime($filter['startDay']));
                $endDay = date('Y-m-d H:i:s', strtotime($filter['endDay']));
                $query = $query->whereDate('created_at','>=' ,$statDay)->whereDate('created_at','<=' ,$endDay);
            }
        }
       // dd($query->toSql() );
        return  $query->with(['user','account','contact'])->orderBy('created_at', 'desc')->paginate($perPage);
    }


}
