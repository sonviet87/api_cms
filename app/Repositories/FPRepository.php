<?php
namespace App\Repositories;

use App\Constants\FPConst;
use App\Interfaces\FPInterface;
use App\Models\ContractCode;
use App\Models\FP;
use App\Models\Supplier;


class FPRepository implements FPInterface {
    protected $model;
    function __construct(FP $fp){
        $this->model = $fp;
    }

    public function getList($filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id'])->orWhere('user_assign', $filter['user_id']) ;
            }
        }
        return $query->get();
    }

    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['search']) && $filter['search'] != '') {
                $query = $query->where('name', 'LIKE', "%{$filter['search']}%")->orWhere('code', 'LIKE', "%{$filter['search']}%") ;
            }
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id'])->orWhere('user_assign', $filter['user_id']) ;

            }
        }
       // dd($query->toSql() );
        return  $query->with(['user','account','contact'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        $fp = $this->model->create($data);
        if($fp->net_profit_percent >= 10) {
            $fp->status = 1;
            $fp->save();
        }
        return $fp;
    }

    public function getByID($id){
        return $this->model->find($id);
    }

    public function update($id, $data){
        return $this->model->find($id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function updateStatus($id, $status){
        $fp = $this->model->find($id);
        if($status == FPConst::STATUS_COMPLETED){
            $fp->selling = $fp->total_sell;
            $fp->margin = $fp->net_profit;
            $fp->save();
        }
        if($status == FPConst::STATUS_CONTRACT){
            if($fp->isCodeContract == 0){
                //increment code contract
                $contractCode = ContractCode::get()->first();
                $contractCode->code = $contractCode->code + 1;
                $contractCode->save();

                //update code contract
                $fp->isCodeContract = 1;
                $fp->code_contract = 'HD-MV-TECH-'. $contractCode->code ;
            }

        }

        return $fp->update(['status'=> $status]);
    }

}
