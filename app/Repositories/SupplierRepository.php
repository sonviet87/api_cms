<?php
namespace App\Repositories;

use App\Interfaces\SupplierInterface;
use App\Models\Supplier;


class SupplierRepository implements SupplierInterface {
    protected $model;
    function __construct(Supplier $supplier){
        $this->model = $supplier;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20){
        return $this->model->with('user')->orderBy('created_at', 'desc')->paginate($perPage);
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
