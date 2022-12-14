<?php

namespace App\Services;


use App\Constants\FPConst;
use App\Constants\RolePermissionConst;
use App\Interfaces\FPDetailInterface;
use App\Interfaces\FPInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FPService extends BaseService
{
    protected $fp;
    protected $fpDetail;

    function __construct(FPInterface $fp,FPDetailInterface $fpDetail)
    {
        $this->fp = $fp;
        $this->fpDetail = $fpDetail;
    }

    public function getList()
    {
        return $this->fp->getList();
    }

    public function getListPaginate($perPage = 20, $filter)
    {

        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");

        /*if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_ADMIN] ||  $role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_CEO] ||  $role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_Manager] ){
            return $this->fp->getListPaginate($perPage, $filter);
        }*/
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
       // dd(Auth::user()->roles->pluck('name')->first());
        return $this->fp->getListPaginate($perPage, $filter);

    }

    public function createNew($data)
    {
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $details= $data['details'];
            $arrFPDetail= [];
            $arrFP = Arr::except($data, ['details']);
            $arrFP['user_id']= $user->id;
            $arrFP['shipping_charges']= Str::replace(",","",$arrFP["shipping_charges"]);
            $arrFP['guest_costs']= Str::replace(",","",$arrFP["guest_costs"]);
            $arrFP['deployment_costs']= Str::replace(",","",$arrFP["deployment_costs"]);
            $arrFP['bids_cost']= Str::replace(",","",$arrFP["bids_cost"]);
            $arrFP['commission']= Str::replace(",","",$arrFP["commission"]);
            $arrFP['interest']= Str::replace(",","",$arrFP["interest"]);
            $arrFP['tax']= Str::replace(",","",$arrFP["tax"]);
            $arrFP['bids_cost_percent']= Str::replace("%","",$arrFP["bids_cost_percent"]);
            $arrFP['commission_percent']= Str::replace("%","",$arrFP["commission_percent"]);
            $arrFP['file_customer_invoice']= isset($arrFP["file_customer_invoice"])?  $arrFP["file_customer_invoice"]: "" ;
            $arrFP['file_company_receipt']= isset($arrFP["file_company_receipt"])?  $arrFP["file_company_receipt"]: "" ;
            $arrFP['file_bbbg']= isset($arrFP["file_bbbg"])?  $arrFP["file_bbbg"]: "" ;
            $arrFP['file_customer_invoice_url']= isset($arrFP["file_customer_invoice_url"]) ?  $arrFP["file_customer_invoice_url"]: "" ;
            $arrFP['file_company_receipt_url']= isset($arrFP["file_company_receipt_url"])?  $arrFP["file_company_receipt_url"]: "" ;
            $arrFP['file_bbbg_url']= isset($arrFP["file_bbbg_url"]) ?  $arrFP["file_bbbg_url"]: "" ;
            $fp = $this->fp->create($arrFP);
            $fp->code = 'FP'.$fp->id;
            $fp->save();

            //create order detail
            foreach ($details as $key => $detail){

                $arrFPDetail[$key]["fp_id"] = $fp->id;
                $arrFPDetail[$key]["price_buy"] = Str::replace(",","",$detail["price_buy"]);
                $arrFPDetail[$key]["price_sell"] = Str::replace(",","",$detail["price_sell"]);
                $arrFPDetail[$key]["total_buy"] = Str::replace(",","",$detail["total_buy"]);
                $arrFPDetail[$key]["total_sell"] = Str::replace(",","",$detail["total_sell"]);
                $arrFPDetail[$key]["profit"] = Str::replace("%","",$detail["profit"]);
                $arrFPDetail[$key]["qty"] = $detail["qty"];
                $arrFPDetail[$key]["category_id"] = $detail["category_id"];
                $arrFPDetail[$key]["supplier_id"] = $detail["supplier_id"];
                $arrFPDetail[$key]["file"] = $detail["file"] ? $detail["file"]: "" ;
                $arrFPDetail[$key]["file_url"] = $detail["file_url"]?  $detail["file_url"]: "" ;
                $arrFPDetail[$key]["created_at"] = Carbon::now();
            }

            $this->fpDetail->create($arrFPDetail);
            DB::commit();
            return $this->_result(true, trans('Tạo phương án kinh doanh thành công'), [
                'fp' => $fp
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->_result(false, $e->getMessage());
        }

    }

    public function getByID($id)
    {
        $account = $this->fp->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
       /* $fpRS = $this->fp->getByID($id);
        if (!$fpRS) {
            return $this->_result(false, 'Not found!');
        }*/


//        $result = $this->fp->update($id, $data);
//        if (!$result) {
//            return $this->_result(false, 'Updated failed');
//        }
//        return $this->_result(true, 'Updated successfully');

        DB::beginTransaction();
        try {
            $details= $data['details'];
            $arrFPDetail= [];
            $arrFP = Arr::except($data, ['details','created_at']);

            $arrFP['shipping_charges']= Str::replace(",","",$arrFP["shipping_charges"]);
            $arrFP['guest_costs']= Str::replace(",","",$arrFP["guest_costs"]);
            $arrFP['deployment_costs']= Str::replace(",","",$arrFP["deployment_costs"]);
            $arrFP['bids_cost']= Str::replace(",","",$arrFP["bids_cost"]);
            $arrFP['commission']= Str::replace(",","",$arrFP["commission"]);
            $arrFP['interest']= Str::replace(",","",$arrFP["interest"]);
            $arrFP['tax']= Str::replace(",","",$arrFP["tax"]);
            $arrFP['bids_cost_percent']= Str::replace("%","",$arrFP["bids_cost_percent"]);
            $arrFP['commission_percent']= Str::replace("%","",$arrFP["commission_percent"]);
            $arrFP['file_customer_invoice']= isset($arrFP["file_customer_invoice"])?  $arrFP["file_customer_invoice"]: "" ;
            $arrFP['file_company_receipt']= isset($arrFP["file_company_receipt"])?  $arrFP["file_company_receipt"]: "" ;
            $arrFP['file_bbbg']= isset($arrFP["file_bbbg"])?  $arrFP["file_bbbg"]: "" ;
            $arrFP['file_customer_invoice_url']= isset($arrFP["file_customer_invoice_url"]) ?  $arrFP["file_customer_invoice_url"]: "" ;
            $arrFP['file_company_receipt_url']= isset($arrFP["file_company_receipt_url"])?  $arrFP["file_company_receipt_url"]: "" ;
            $arrFP['file_bbbg_url']= isset($arrFP["file_bbbg_url"]) ?  $arrFP["file_bbbg_url"]: "" ;

            $fp = $this->fp->update($id,$arrFP);
            //create order detail
            foreach ($details as $key => $detail){
                $arrFPDetail[$key]["fp_id"] = $id;
                $arrFPDetail[$key]["price_buy"] = Str::replace(",","",$detail["price_buy"]);
                $arrFPDetail[$key]["price_sell"] = Str::replace(",","",$detail["price_sell"]);
                $arrFPDetail[$key]["total_buy"] = Str::replace(",","",$detail["total_buy"]);
                $arrFPDetail[$key]["total_sell"] = Str::replace(",","",$detail["total_sell"]);
                $arrFPDetail[$key]["profit"] = Str::replace("%","",$detail["profit"]);
                $arrFPDetail[$key]["qty"] = $detail["qty"];
                $arrFPDetail[$key]["category_id"] = $detail["category_id"];
                $arrFPDetail[$key]["supplier_id"] = $detail["supplier_id"];
                $arrFPDetail[$key]["file"] = $detail["file"] ? $detail["file"]: "" ;
                $arrFPDetail[$key]["file_url"] = $detail["file_url"]?  $detail["file_url"]: "" ;
                $arrFPDetail[$key]["created_at"] = Carbon::now();
                if(isset($detail['id'])){
                    $this->fpDetail->update($detail['id'],$arrFPDetail[$key]);
                }else{
                    $this->fpDetail->create($arrFPDetail[$key]);
                }

            }

            /*$ids = $this->fpDetail->getIDS($id);
            $this->fpDetail->destroy($ids);
            $this->fpDetail->create($arrFPDetail);*/
            DB::commit();
            return $this->_result(true, trans('Cập nhật phương án kinh doanh thành công'), [
                'fp' => $fp
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->_result(false, $e->getMessage());
        }
    }

    public function destroyByIDs($ids)
    {
        $check = $this->fp->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Không thể xóa PAKD thành công');
        }
        return $this->_result(true, 'Xóa PAKD thành công');
    }

    public function updateStatus($id, $status)
    {

        $check = $this->fp->updateStatus($id,$status);

        if (!$check) {
            return $this->_result(false, 'Lỗi');
        }
        return $this->_result(true, 'Cập nhật thành công');
    }

}
