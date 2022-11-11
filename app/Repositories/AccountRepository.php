<?php
namespace App\Repositories;
use App\Interfaces\AccountInterface;
use App\Models\Account;


class AccountRepository implements AccountInterface {
    protected $model;
    function __construct(Account $account){
        $this->model = $account;
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

    public function getListContactByID($id ){

        return $this->model->find($id)->contacts;
    }
}
