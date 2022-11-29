<?php
namespace App\Repositories;

use App\Interfaces\FPInterface;
use App\Models\FP;
use App\Models\Supplier;


class FPRepository implements FPInterface {
    protected $model;
    function __construct(FP $fp){
        $this->model = $fp;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20){
        return $this->model->with(['user','account','contact'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        return $this->model->create($data);
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
