<?php
namespace App\Repositories;



use App\Interfaces\ContactInterface;
use App\Models\Contact;

class ContactRepository implements ContactInterface {
    protected $model;
    function __construct(Contact $contact){
        $this->model = $contact;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20){
        return $this->model->with(['users','accounts'])->orderBy('created_at', 'desc')->paginate($perPage);
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
