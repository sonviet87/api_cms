<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountCollection;
use App\Http\Resources\ContactCollection;

use App\Http\Resources\WarrantyCollection;
use App\Http\Resources\WarrantyResource;
use App\Services\WarrantyService;
use Illuminate\Http\Request;


class WarrantyController extends RestfulController
{
    protected $warrantyService;

    public function __construct(WarrantyService $warrantyService)
    {
        parent::__construct();
        $this->warrantyService = $warrantyService;

        /*$this->middleware(['permission:account-delete'])->only('destroy');
        $this->middleware(['permission:account-create'])->only('store');
        $this->middleware(['permission:account-edit'])->only('update');
        $this->middleware(['permission:account-list'])->only('index');*/
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $warranty = $this->warrantyService->getListPaginate($perPage);

            return new WarrantyCollection($warranty);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function list(Request $request)
    {
        try {

            $warranty = $this->warrantyService->getList();
            return new WarrantyCollection($warranty);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Account
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->warrantyService->createNew($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get a account by id
     * @param interger $id
     * @return mixed
     */
    public function show($id){
        try{
            $result = $this->warrantyService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
           // return $this->_response($result['data']);
            return new WarrantyResource($result['data']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a account by  id
     * @return mixed
     */
    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'bail|required'
        ]);
        try{
            $data = $request->all();
            $result = $this->warrantyService->update($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of warranty by an array of  id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){

        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->warrantyService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
