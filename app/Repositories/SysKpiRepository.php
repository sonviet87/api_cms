<?php
namespace App\Repositories;

use App\Interfaces\SysKpiInterface;
use App\Models\SysKpi;


class SysKpiRepository implements SysKpiInterface {
    protected $model;
    function __construct(SysKpi $sysKpi){
        $this->model = $sysKpi;
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
