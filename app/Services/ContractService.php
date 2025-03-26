<?php

namespace App\Services;

use App\Interfaces\AccountInterface;
use App\Interfaces\ContractInterface;

class ContractService extends BaseService
{
    protected $contract;

    function __construct(ContractInterface $contract)
    {
        $this->contract = $contract;
    }

    public function getList()
    {
        return $this->contract->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->contract->getListPaginate($perPage);
    }

    public function create($data)
    {
        $account = $this->contract->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->contract->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->contract->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->contract->updateByID($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->contract->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
