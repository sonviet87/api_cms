<?php
namespace App\Repositories;


use App\Interfaces\KpiSettingStaffManagerInterface;
use App\Models\KpiSettingStaffManager;

class KpiSettingStaffManagerRepository implements KpiSettingStaffManagerInterface {
    protected $model;
    function __construct(KpiSettingStaffManager $kpiSettingStaffManager){
        $this->model = $kpiSettingStaffManager;
    }

    public function getList($filter){
        $query = $this->model;
        return $query->orderBy('id', 'desc')->get();
    }

    public function getListPaginate($perPage = 20,$filter){
        $query = $this->model;
        if (isset($filter['search']) && $filter['search'] != '') {
            $search = $filter['search'];
             $query = $query->where('name', 'LIKE', "%{$search}%") ;

        }
        return $query ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        return $this->model->insert($data);
    }

    public function getByID($id){
        return $this->model->find($id);
    }

    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function findFirstKpiStaff($user_id,$kpi_id,$type,$type_kpi = 'sale'){
        return $this->model->where('user_id', $user_id)
            ->where('kpi_setting_sale_id', $kpi_id)
            ->where('type', $type)
            ->where('kpi_type', $type_kpi)
            ->first();
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }


}
