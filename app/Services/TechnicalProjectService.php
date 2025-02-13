<?php

namespace App\Services;


use App\Interfaces\TechnicalProjectInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class TechnicalProjectService extends BaseService
{

    protected $technicalProject;

    function __construct(TechnicalProjectInterface $technicalProject)
    {
        $this->technicalProject = $technicalProject;
    }

    public function getAll($IDs)
    {
        return $this->technicalProject->getAll($IDs);
    }

    public function getList($perPage,$filter)
    {

        return $this->technicalProject->getList($perPage,$filter);
    }

    public function create($data)
    {
        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        $rs = $this->technicalProject->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->technicalProject->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }

    public function update($id, $data)
    {

        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        $rs = $this->technicalProject->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }

        $result = $this->technicalProject->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->technicalProject->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
