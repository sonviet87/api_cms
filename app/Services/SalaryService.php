<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\AccountInterface;
use App\Interfaces\SalaryInterface;
use Illuminate\Support\Facades\Auth;

class SalaryService extends BaseService
{
    protected $salary;

    function __construct(SalaryInterface $salary)
    {
        $this->salary = $salary;
    }

    public function getList()
    {
        $filter=[];
        return $this->salary->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->salary->getListPaginate($perPage,$filter);
    }



    public function create($data)
    {
        $rs = $this->salary->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $rs = $this->salary->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $rs);
    }

    public function update($id, $data)
    {
        $rs = $this->salary->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->salary->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->salary->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
