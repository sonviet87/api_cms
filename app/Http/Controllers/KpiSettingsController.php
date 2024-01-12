<?php

namespace App\Http\Controllers;

use App\Http\Resources\KpiSettingsCollection;
use App\Http\Resources\KpiSettingsResource;
use App\Services\KpiSettingsService;
use Illuminate\Http\Request;


class KpiSettingsController extends RestfulController{

    protected  $kpiSettings;

    public function __construct(KpiSettingsService $kpiSettings)
    {
        parent::__construct();
        $this->kpiSettings = $kpiSettings;

    }

    /**
     * Get all  Kpi settings
     * @return mixed
     */
    public function index(Request $request)
    {
        try {

            $rs = $this->kpiSettings->getList();

            return new KpiSettingsCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Kpi members groups
     * @return mixed
     */
    public function store(Request $request){

        try{
            $data = $request->all();
            $result = $this->kpiSettings->create($data);
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
            $result = $this->kpiSettings->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            // return $this->_response($result['data']);
            return  new KpiSettingsResource($result['data']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a kpi member group  by  id
     * @return mixed
     */
    public function update(Request $request, $id){

        $this->validate($request, [
           // 'name' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->kpiSettings->update($id, $data);
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

    }
}
