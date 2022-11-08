<?php

namespace App\Services;

use App\Interfaces\CategoryInterface;


class CategoryService extends BaseService
{
    protected $category;

    function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    public function getList()
    {
        return $this->category->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->category->getListPaginate($perPage);
    }

    public function createNew($data)
    {
        $account = $this->category->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->category->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->category->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->category->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->category->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
