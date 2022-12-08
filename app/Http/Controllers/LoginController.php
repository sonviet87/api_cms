<?php

namespace App\Http\Controllers;

use App\Constants\UserConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RoleService;
use App\Services\UserService;
class LoginController extends RestfulController
{
    public function __construct(RoleService $roleService, UserService $userService)
    {
        parent::__construct();
        $this->roleService = $roleService;
        $this->userService = $userService;
    }
    /**
     * Login by email and password
     * @param  string $email
     * @param  string $password
     * @return array
     */
    public function loginByEmail(Request $request){
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'required|min:6|max:20',
        ]);

        try{
            $email = $request->input('email');
            $password = $request->input('password');
            if (Auth::attempt(['email'=>$email,'password'=>$password, 'status'=>UserConst::STATUS_ACTIVE])) {
                $user = Auth::user();
                $this->_customUserData($user);
                return response()->result($user, 'Login success', 200);
            }else{
                return $this->_error('Login failed');
            }
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Login by username and password
     * @param  string $user
     * @param  string $password
     * @return array
     */
    public function loginByUsername(Request $request){

        $this->validate($request, [
            'username' => 'bail|required',
            'password' => 'required|min:6|max:20',
        ]);
        try{

            $username = $request->input('username');
            $password = $request->input('password');
            if (Auth::attempt(['username'=>$username,'password'=>$password, 'status'=>UserConst::STATUS_ACTIVE])) {
                $user = Auth::user();
                $this->_customUserData($user, 'JWT-VBA-USERNAME');
              
                return $this->_response($user, 'Login success');
            }else{
                return $this->_error('Login failed');
            }
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    private function _customUserData(&$user, $token_name = 'JWT-VBA-EMAIL'){
        $user->scopes = $this->roleService->getScopesByUser($user);
        //explode(' ', $user->scopes)
        $user->accessToken = $user->createToken($token_name,  $user->scopes)->accessToken;

    }
}
