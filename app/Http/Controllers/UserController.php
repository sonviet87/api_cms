<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Services\UserService;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;


class UserController extends RestfulController
{
    protected $roleService;
    protected $userService;
    protected $user;
    public function __construct( RoleService $roleService, UserService $userService, UserInterface $userInterface){
        parent::__construct();

        $this->roleService = $roleService;
        $this->userService = $userService;
        $this->user = $userInterface;
    }
    /**
     * Get all approved users with paginate
     * @return mixed
     */
    public function index(Request $request){
        try{
            $perPage = $request->input("per_page", 20);
            $username = $request->input("username", '');
            $filter = [
                'username'  => $username,
            ];
            $users = $this->userService->getListPaginate($perPage,$filter);
            $users->appends($request->except(['page', '_token']));
            $paginator = $this->getPaginator($users);
            $pagingArr = $users->toArray();
            return $this->_response([
                'pagination' => $paginator,
                'users' => $pagingArr['data']
            ]);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
    * Register a client
    * @return array
    */
    public function register(Request $request){

        $this->validate($request, [
            'name' => 'bail|required',
            'username' => 'bail|required|min:3|max:20|unique:users,username',
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'bail|required|min:6|max:20',
        ]);

        try{
            $requestData = $request->all();
            $user = $this->userService->registerByEmail($requestData);
            $this->_customUserData($user);
            return $this->_response($user, 'Register success');
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    private function _customUserData(&$user, $token_name = 'JWT-VBA-EMAIL'){
        $user->scopes = $this->roleService->getScopesByUser($user);
        $user->accessToken = $user->createToken($token_name, explode(' ', $user->scopes))->accessToken;
    }

    /**
    * Check email exist in DB
    * @return array
    */
    public function checkEmail(Request $request){
        $this->validate($request, [
            'email' => 'bail|required|email',
        ]);
        try{
            $email = $request->input('email');
            $checkEmail = $this->user->getUserByEmail($email);
            if(!$checkEmail){
                return $this->_response(['result'=> false]);
            }
            return $this->_response(['result'=> true]);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
    * Check phone exist in DB
    * @return array
    */
    public function checkPhone(Request $request){
        $this->validate($request, [
            'phone' => 'bail|required',
        ]);
        try{
            $phone = $request->input('phone');
            $checkPhone = $this->user->getUserByPhone($phone);
            if(!$checkPhone){
                return $this->_response(['result'=> false]);
            }
            return $this->_response(['result'=> true]);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a user
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'bail|required',
            'username' => 'bail|min:3|max:20|unique:users,username',
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'bail|required|min:6|max:20',
            'role_id' => 'bail|required|exists:roles,id',
           // 'phone' => 'bail|required|unique:users,phone',
           /* 'gender' => 'bail|nullable|integer',
            'birthday' => 'bail|nullable|date_format:Y-m-d',*/
        ]);
        try{
            $data = $request->all();

            $result = $this->userService->createNewUser($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){

            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get a user by id
     * @param interger $id
     * @return mixed
     */
    public function show($id){
        try{
            $result = $this->userService->getUserByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a user by user id
     * @return mixed
     */
    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'bail|required',
            'email' => 'bail|required|email',
            'password' => 'nullable|min:6|max:20'
        ]);
        try{
            $data = $request->all();
            $result = $this->userService->updateUserByID($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of users by an array of user id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->userService->destroyUsersByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function getUser(){
        return Auth::user();
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->_success('Successfully logged out');
    }
}
