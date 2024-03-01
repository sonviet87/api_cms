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
        $query = $this->model;
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $user_id = $filter['user_id'];
            $query = $query->where('user_id', $user_id);
        }
        return $query->all();
    }

    public function getListPaginate($perPage = 20){
        $query = $this->model;
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $user_id = $filter['user_id'];
            $query = $query->where('user_id', $user_id);
        }
        return $query->with(['users','accounts'])->orderBy('created_at', 'desc')->paginate($perPage);
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
