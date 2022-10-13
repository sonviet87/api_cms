<?php
namespace App\Repositories;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Constants\UserConst;

class UserRepository implements UserInterface {
    protected $model;
    function __construct(User $user){
        $this->model = $user;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getUserByEmail($email){
        return $this->model->whereEmail($email)->first();
    }

    public function getUserByPhone($phone){
        return $this->model->wherePhone($phone)->first();
    }

    public function createNewUserByEmail($data){
        $user = new User();
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
       /*  $user->phone = $data['phone'];
        if(isset($data['gender'])){
            $user->gender = $data['gender'];
        }
        if(isset($data['birthday'])){
            $user->birthday = $data['birthday'];
        }
        if(isset($data['address'])){
            $user->address = $data['address'];
        } */
        if(!isset($data['status'])){
            $user->status = UserConst::STATUS_UNACTIVE;
        }else{
            $user->status = $data['status'];
        }
        $user->role_id = $data['role_id'];

        $user->save();
        return $user;
    }
    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['username']) && $filter['username'] != 0) {
                $query = $query->where('username', 'LIKE', "%{$filter['username']}%") ;
            }
        }
        return $query->with(['roles.permissions'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function createNewUser($data){
        return $this->model->create($data);
    }

    public function getUserByID($id){
        return $this->model->find($id);
    }

    public function updateUserByID($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function destroyUsersByIDs($ids){
        return $this->model->destroy($ids);
    }

    public function getBySimilarPhone($phone){
        return $this->model->where('phone', 'like', '%'.$phone.'%')->get();
    }
}
