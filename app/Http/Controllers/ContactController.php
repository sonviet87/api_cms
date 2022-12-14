<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactCollection;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends RestfulController
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;
        //$this->middleware(['permission:account-list|account-create|account-edit|account-delete']);
       $this->middleware(['permission:contact-delete'])->only('destroy');
        $this->middleware(['permission:contact-create'])->only('store');
        $this->middleware(['permission:contact-edit'])->only('update');
        $this->middleware(['permission:contact-list'])->only('index');
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $contacts = $this->contactService->getListPaginate($perPage);
            return new ContactCollection($contacts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get all
     * @return mixed
     */
    public function list(Request $request)
    {
        try {

            $contacts = $this->contactService->getList();
            return new ContactCollection($contacts);
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
            $result = $this->contactService->createNew($data);
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
            $result = $this->contactService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data']);
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
            $result = $this->contactService->update($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of account by an array of  id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->contactService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
