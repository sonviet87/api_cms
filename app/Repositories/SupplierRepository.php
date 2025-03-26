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

    public function getListPaginate($perPage = 20,$filter=[]){
        $query = $this->model;
        if (isset($filter['search']) && $filter['search'] != '') {
            $search = $filter['search'];
            $query = $query->where('company', 'LIKE', "%{$search}%") ;
        }
        return $query->with('user')->orderBy('created_at', 'desc')->paginate($perPage);

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


    public function getListNewSuplierbyUsers($filter = []){
        $query = $this->model;

        if (isset($filter['selectedYear']) && $filter['selectedYear'] != '') {
            $query = $query->where('is_new','=' ,$filter['selectedYear']);
        }

        if (isset($filter['users'])) {

            $query = $query->where('user_id', $filter['users']) ;
        }
        //dd($query->toSql());
        return $query->orderBy('id', 'desc')->get();

    }

    public function getOldSupplierIncreaseDebts($filter = []){

        $query = $this->model;
        $year  = $filter['selectedYear'];
        if (isset($filter['users']) && $filter['users'] != '') {
            $query = $query->where('user_id', $filter['users']);
        }
        $query = $query->where(function ($query) use ($year) {
            $query->where('increase_debt_times', $year)
            ->orWhere(function ($query) use ($year) {
                $query->whereNotNull('history')
                    ->whereRaw("JSON_CONTAINS(history, JSON_OBJECT('year', ?))", [$year]);
            });
        });
       //dd($query->toSql()) ;
        return $query->orderBy('id', 'desc')->get();
    }

}
