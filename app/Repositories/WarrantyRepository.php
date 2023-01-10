<?php
namespace App\Repositories;

use App\Interfaces\WarrantyInterface;
use App\Models\Warranty;


class WarrantyRepository implements WarrantyInterface {
    protected $model;
    function __construct(Warranty $warranty){
        $this->model = $warranty;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20){
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
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


}