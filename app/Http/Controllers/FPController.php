<?php

namespace App\Http\Controllers;


use App\Http\Resources\FPCollection;
use App\Http\Resources\FPResource;
use App\Http\Resources\SupplierCollection;
use App\Mail\MailNotify;
use App\Services\FPService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FPController extends RestfulController
{
    protected $fpService;

    public function __construct(FPService $fpService)
    {
        parent::__construct();
        $this->fpService = $fpService;
        //$this->middleware(['permission:fp-approved-manager|fp-approved-sale|fp-list|fp-create|fp-edit|fp-delete']);
        $this->middleware(['permission:fp-delete'])->only('destroy');
        $this->middleware(['permission:fp-create'])->only('store');
        $this->middleware(['permission:fp-edit'])->only('update');
        $this->middleware(['permission:fp-list'])->only('index');
        $this->middleware(['permission:fp-approved-manager|fp-approved-sale'])->only('updateStatus');
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $search = $request->input("search", '');
            $filter = [
                'search'  => $search,
            ];
            $suppliers = $this->fpService->getListPaginate($perPage, $filter);

            return new FPCollection($suppliers);
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
            $suppliers = $this->fpService->getList();
            return new FPCollection($suppliers);
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
            'account_id' => 'bail|required',
            'contact_id' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->fpService->createNew($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }

            if($result['status']){
               // Mail::to('sonviet87@gmail.com')->cc($result['data']['email_assgin'])->send(new MailNotify($result['data']));
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
            $result = $this->fpService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
           // return $this->_response($result['data']);
            return  new FPResource($result['data']);
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
            'name' => 'bail|required',
            'account_id' => 'bail|required',
            'contact_id' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->fpService->update($id, $data);
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
            $result = $this->fpService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function updateStatus(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required',
        ]);
        try{
            $id = $request->input('id');
            $status = $request->input('status');

            $result = $this->fpService->updateStatus($id, $status);

            if($result['status']==false){
                return $this->_error($result['message']);
            }

            if($result['status']){
               // Mail::to('sonviet87@gmail.com')->cc($result['data']['email_assgin'])->send(new MailNotify($result['data']));
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
