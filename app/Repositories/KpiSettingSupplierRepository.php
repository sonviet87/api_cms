<?php
namespace App\Repositories;

use App\Interfaces\KpiSettingSupplierInterface;
use App\Models\KpiSettingSupplier;
use Illuminate\Support\Facades\Auth;


class KpiSettingSupplierRepository implements KpiSettingSupplierInterface {
    protected $model;
    function __construct(KpiSettingSupplier $kpiSettingSupplier){
        $this->model = $kpiSettingSupplier;
    }

    public function getList($filter){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['year']) && $filter['year'] != '') {
                $query = $query->where('year', $filter['year']) ;
            }

        }
        if (!Auth::user()->hasPermissionTo('kpi-supplier-all-user')) {
            $query = $query->where('user_id', Auth::id());
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function getListPaginate($perPage = 20,$filter){
        $query = $this->model;
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $user_id = $filter['user_id'];
            $query = $query->where('user_id', $user_id);
        }
        if (isset($filter['search']) && $filter['search'] != '') {
            $search = $filter['search'];
             $query = $query->where('name', 'LIKE', "%{$search}%") ;

        }
        return $query ->orderBy('created_at', 'desc')->paginate($perPage);
    }


    public function create($data){
        return $this->model->create($data);
    }

    public function getByID($id){
        return $this->model->find($id);
    }


    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }
    public function getByUserID($id){
        return $this->model->where('user_id',$id);
    }

}
