<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountCollection;
use App\Http\Resources\ContactCollection;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends RestfulController
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        parent::__construct();
        $this->accountService = $accountService;

        $this->middleware(['permission:account-delete'])->only('destroy');
        $this->middleware(['permission:account-create'])->only('store');
        $this->middleware(['permission:account-edit'])->only('update');
        $this->middleware(['permission:account-list'])->only('index');
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
            $accounts = $this->accountService->getListPaginate($perPage,$filter);
           // $accounts->appends($request->except(['page', '_token']));
           // $paginator = $this->getPaginator($accounts);
           // $pagingArr = $accounts->toArray();
           /* return $this->_response([
                'pagination' => $paginator,
                'account' => $pagingArr['data']
            ]);*/
            return new AccountCollection($accounts);
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

            $accounts = $this->accountService->getList();
            return new AccountCollection($accounts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get contact by id
     * @return mixed
     */
    public function getListContactByID(Request $request,$id)
    {
        try {
            $contacts = $this->accountService->getListContactByID($id);

            if(isset($contacts['status'])){
                return $this->_error($contacts['message']);
            }
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
            $result = $this->accountService->createNewAccount($data);
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
            $result = $this->accountService->getAccountByID($id);
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
            $result = $this->accountService->update($id, $data);
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
       // dd(Auth::user()->first()->hasPermission(['account-list']));
        try{
            $ids = $request->input('ids');
            $result = $this->accountService->destroyAccountByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
