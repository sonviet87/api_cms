<?php

namespace App\Services;


use App\Interfaces\WarrantyInterface;

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

    public function getListPaginate($perPage = 20)
    {
        return $this->warranty->getListPaginate($perPage);
    }

    public function getListContactByID($id)
    {
        $contacts =  $this->warranty->getListContactByID($id);
        if (!$contacts) {
            return $this->_result(false, 'Không lấy được id');
        }
        return  $contacts;
    }

    public function createNew($data)
    {
        $account = $this->warranty->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->warranty->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->warranty->getByID($id);
        if (!$account) {
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
