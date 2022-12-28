<?php

namespace App\Services;





use App\Interfaces\DebtSupplierInterface;
use App\Interfaces\FPDetailInterface;

class DebtSupplierService extends BaseService
{
    protected $debt;
    protected $fpDetail;

    function __construct(DebtSupplierInterface $debt, FPDetailInterface $fpDetail)
    {
        $this->debt = $debt;
        $this->fpDetail = $fpDetail;

    }

    public function getList()
    {
        return $this->debt->getList();
    }

    public function getSupplierbyIDFP($idSupplier,$idFP){

        return $this->fpDetail->getSupplierbyIDFP($idSupplier,$idFP);
    }

    public function getListSupplierbyIDFP($idFP){

        return $this->fpDetail->getListSupplierbyIDFP($idFP);
    }

    public function getListPaginate($perPage = 20, $filter)
    {

        return $this->debt->getListPaginate($perPage, $filter);

    }

    public function createNew($data)
    {
        if(isset($data["date_over"])) $data["date_over"] =  date('Y-m-d H:i:s', strtotime($data["date_over"]));
        $account = $this->debt->create($data);
        if (!$account) {
            return $this->_result(false, 'Tạo công nợ không thành công');
        }
        return $this->_result(true, 'Tạo công nợ thành công');

    }

    public function getByID($id)
    {
        $account = $this->debt->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Không tìm thấy!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->debt->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Không tìm thấy!');
        }


        $result = $this->debt->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Cập nhật không thành công');
        }
        return $this->_result(true, 'Cập nhật thành công');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->debt->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Không thể xóa công nợ');
        }
        return $this->_result(true, 'Xóa công nợ thành công');
    }



}
