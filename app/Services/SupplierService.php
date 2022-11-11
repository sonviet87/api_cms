<?php

namespace App\Services;


use App\Interfaces\SupplierInterface;


class SupplierService extends BaseService
{
    protected $supplier;

    function __construct(SupplierInterface $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getList()
    {
        return $this->supplier->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->supplier->getListPaginate($perPage);
    }

    public function createNew($data)
    {
        $account = $this->supplier->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->supplier->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->supplier->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->supplier->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->supplier->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
