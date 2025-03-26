<?php

namespace App\Http\Controllers;

use App\Http\Resources\KpiSettingTechnicalResource;
use App\Http\Resources\KpiSetUpUserCollection;
use App\Services\KpiSettingTechnicalrService;
use Illuminate\Http\Request;


class KpiSettingTechnicalController extends RestfulController{

    protected  $kpiSettingTechnicalrService;

    public function __construct(KpiSettingTechnicalrService $kpiSettingTechnicalrService)
    {
        parent::__construct();
        $this->kpiSettingTechnicalrService = $kpiSettingTechnicalrService;

    }
    /**
     * Get all  Kpi members groups with paginate
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
            $rs = $this->kpiSettingTechnicalrService->getListPaginate($perPage, $filter);

            return new KpiSetUpUserCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get all  Kpi members groups with paginate
     * @return mixed
     */
    public function list(Request $request)
    {
        $this->validate($request, [

            'year' => 'bail|required',
        ]);
        try {
            $search = $request->input("search", '');
            $year = $request->input("year", '');
            $filter = [
                'search'  => $search,
                'year'  => $year,
            ];
            $rs = $this->kpiSettingTechnicalrService->getList($filter);
            return new KpiSetUpUserCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Kpi members groups
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'bail|required',
            'user_id' => 'bail|required',
            'year' => 'bail|required',
        ]);

        try{
            $data = $request->all();
            $result = $this->kpiSettingTechnicalrService->create($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get a kpi member group by id
     * @param interger $id
     * @return mixed
     */
    public function show($id){
        try{
            $result = $this->kpiSettingTechnicalrService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return  new KpiSettingTechnicalResource($result['data']);
        }catch(\Exception $e){
            /*return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại',
            ], Response::HTTP_NOT_FOUND);*/
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a kpi member group  by  id
     * @return mixed
     */
    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->kpiSettingTechnicalrService->update($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of kpi member group by an array of  id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->kpiSettingTechnicalrService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
}
