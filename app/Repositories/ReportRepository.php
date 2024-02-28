<?php
namespace App\Repositories;

use App\Constants\FPConst;
use App\Interfaces\ReportInterface;
use App\Models\FP;
use Carbon\Carbon;

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
                $statDay = date('Y-m-d',strtotime($filter['startDay']));
                $endDay = date('Y-m-d', strtotime($filter['endDay']));
               // $startDate = Carbon::createFromFormat('Y-m-d', $statDay)->startOfDay();
               // $endDate = Carbon::createFromFormat('Y-m-d', $endDay)->startOfDay();
                //dd($endDate);
                if (isset($filter['type_fp']) && $filter['type_fp'] != '' && $filter['type_fp']== FPConst::STATUS_COMPLETED) {
                    $query = $query->whereDate('date_completed', '>=', $statDay)->whereDate('date_completed', '<=', $endDay);
                }else{
                    $query = $query->whereDate('created_at', '>=', $statDay)->whereDate('created_at', '<=', $endDay);
                }
            }
        }
       // dd($query->toSql() );
        $query = $query->with(['user','account','contact'])->orderBy('created_at', 'desc');

        if(isset($filter['list']) && $filter['list'] == 'list') return  $query->get();

        return  $query->paginate($perPage);
    }




}
