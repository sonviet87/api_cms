<?php
namespace App\Repositories;

use App\Interfaces\PositionInterface;
use App\Models\Position;

class PositionRepository implements PositionInterface {
    protected $model;
    function __construct(Position $position){
        $this->model = $position;
    }

    public function getList($filter=[]){
        $query = $this->model;
        return $query->orderBy('id', 'desc')->get();
    }

    public function getAll($data){
        $query = $this->model;

        $query = $query->whereIn('id', $data) ;

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
