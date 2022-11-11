<?php
namespace App\Repositories;
use App\Interfaces\UserInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Constants\UserConst;
use Spatie\Permission\Models\Role;

class UserRepository implements UserInterface {
    protected $model;
    protected $role;
    function __construct(User $user,Role $role){
        $this->model = $user;
        $this->role = $role;
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
        $idRole = $data['role_id'];
        $data = Arr::except($data,['role_id']);
        $user = $this->model->create($data);
        $role = $this->role->find($idRole);

        if (!empty($role) ) {
            $user->syncRoles([$role->name]);
        }
        return $user;
    }

    public function getUserByID($id){
        return $this->model->with('roles')->find($id);
    }

    public function updateUserByID($id, $data){
        $idRole = $data['role_id'];
        $data = Arr::except($data,['role_id']);
        $user = $this->model->find($id);
        $user->update($data);
        $role = $this->role->find($idRole);

        if (!empty($role) ) {
           $user->syncRoles([$role->name]);
        }

        return $user;
    }

    public function destroyUsersByIDs($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getBySimilarPhone($phone){
        return $this->model->where('phone', 'like', '%'.$phone.'%')->get();
    }
}
