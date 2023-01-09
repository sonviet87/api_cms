<?php
namespace App\Repositories;

use App\Constants\FPConst;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Models\Debt;
use App\Models\FP;
use App\Models\Supplier;


class DebtRepository implements DebtInterface {
    protected $model;
    function __construct(Debt $debt){
        $this->model = $debt;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['search']) && $filter['search'] != '') {
                $search = $filter['search'];
                $query = $query->where('name', 'like', "%{$filter['search']}%")
                ->orWhereHas('fp', function ($query) use ( $search){
                    $query->where('code', 'like', '%'. $search.'%')
                    ->orWhereHas('account', function ($query) use ( $search){
                        $query->where('name', 'like', '%'. $search.'%');
                    }) ;;
                }) ;
            }

        }
        //dd($query->toSql() );
        return  $query->with(['fp'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        $debt = $this->model->create($data);
        $debt->code = 'CNKH'.$debt->id;
        $debt->save();
        return $debt;
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



}