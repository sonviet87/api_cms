<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Imports\CategoriesImport;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends RestfulController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        //$this->middleware(['permission:account-list|account-create|account-edit|account-delete']);
        $this->middleware(['permission:category-delete'])->only('destroy');
        $this->middleware(['permission:category-create'])->only('store');
        $this->middleware(['permission:category-edit'])->only('update');
        $this->middleware(['permission:category-list'])->only('index');
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $cateogries = $this->categoryService->getListPaginate($perPage);

            return new CategoryCollection($cateogries);
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
            $search = $request->input("search", '');
            $filter = [
                'search'  => $search,
            ];
            $cateogries = $this->categoryService->getList($filter);

            return new CategoryCollection($cateogries);
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
            $result = $this->categoryService->createNew($data);
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
            $result = $this->categoryService->getByID($id);
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
            $result = $this->categoryService->update($id, $data);
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
            $result = $this->categoryService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }


    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|min:1',
        ]);

        try {
            Excel::import(new CategoriesImport(), $request->file('file'));
            return $this->_success("Nhập dữ liệu thành công");
        }catch (\Exception $ex){
            //return $this->_error("Không đúng định dạng", self::HTTP_INTERNAL_ERROR);
           // return response()->error("Không đúng định dạng", null, self::HTTP_INTERNAL_ERROR);
            return $this->_error('Không đúng định dạng');
        }



    }

}
