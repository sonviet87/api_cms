<?php
namespace App\Repositories;

use App\Interfaces\KpiSettingsInterface;
use App\Models\KpiDebts;
use App\Models\KpiSettings;


class KpiSettingsRepository implements KpiSettingsInterface {
    protected $model;
    function __construct(KpiSettings $settings){
        $this->model = $settings;
    }

    public function getList(){
        $query = $this->model;
        return $query->orderBy('id', 'asc')->get();
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


    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }


}
