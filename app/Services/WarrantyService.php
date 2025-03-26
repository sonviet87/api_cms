<?php

namespace App\Services;


use App\Interfaces\WarrantyInterface;
use Carbon\Carbon;

class WarrantyService extends BaseService
{
    protected $warranty;

    function __construct(WarrantyInterface $warranty)
    {
        $this->warranty = $warranty;
    }

    public function getList()
    {
        return $this->warranty->getList();
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->warranty->getListPaginate($perPage,$filter);
    }


    public function createNew($data)
    {
        $data['details']= json_encode($data['details']);
        if(isset($data["start_day"])) $data["start_day"] =  Carbon::parse($data["start_day"])->toDateTimeString();
        if(isset($data["end_day"])) $data["end_day"] =  Carbon::parse($data["end_day"])->toDateTimeString();
        $warranty = $this->warranty->create($data);
        if (!$warranty) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $warranty = $this->warranty->getByID($id);
        if (!$warranty) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $warranty);
    }

    public function update($id, $data)
    {   $data['details']= json_encode($data['details']);
        if(isset($data["start_day"])) $data["start_day"] =  Carbon::parse($data["start_day"])->toDateTimeString();
        if(isset($data["end_day"])) $data["end_day"] =  Carbon::parse($data["end_day"])->toDateTimeString();
        $warranty = $this->warranty->getByID($id);
        if (!$warranty) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->warranty->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->warranty->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
