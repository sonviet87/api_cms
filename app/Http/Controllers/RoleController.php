<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends RestfulController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
        //$this->middleware(['role:admin','permission:account-list|account-create|account-edit|account-delete']);
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $roles = $this->roleService->getListPaginate($perPage);
            return new RoleCollection($roles);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);
        try{
            $data = $request->all();

            $roleName = $data['name'];
            $permissions = $data['permissions'];

            $result = $this->roleService->create($roleName,$permissions);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){

            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function show($id){
        try{
            $result = $this->roleService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a role by id
     * @return mixed
     */
    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
        ]);
        try{
            $data = $request->all();
            $roleName = $data['name'];
            $permissions = $data['permissions'];
            $result = $this->roleService->update($id, $roleName,$permissions);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of roles by an array of role id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->roleService->destroy($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }


}
