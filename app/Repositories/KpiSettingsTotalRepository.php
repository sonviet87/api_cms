<?php
namespace App\Repositories;

use App\Interfaces\KpiSettingsInterface;
use App\Interfaces\KpiSettingTotalInterface;
use App\Models\KpiDebts;
use App\Models\KpiSettings;
use App\Models\KpiSettingTotal;


class KpiSettingsTotalRepository implements KpiSettingTotalInterface {
    protected $model;
    function __construct(KpiSettingTotal $settings){
        $this->model = $settings;
    }

    public function getList($orderby='id'){
        $query = $this->model;
        return $query->orderBy($orderby, 'asc')->get();
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


    public function getListPaginate($perPage = 20, $filter)
    {
        // TODO: Implement getListPaginate() method.
    }

    public function insert($data)
    {
        return $this->model->insert($data);
    }
}
