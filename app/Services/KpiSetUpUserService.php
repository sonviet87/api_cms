<?php

namespace App\Services;


use App\Interfaces\KpiCustomerInterface;
use App\Interfaces\KpiDebtsInterface;
use App\Interfaces\KpiSettingDebtsItemInterface;
use App\Interfaces\KpiSettingSaleItemInterface;
use App\Interfaces\KpiSettingStaffManagerItemInterface;
use App\Interfaces\KpiSetUpUserInterface;
use App\Repositories\KpiSettingStaffManagerRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KpiSetUpUserService extends BaseService
{
    protected $kpiSetUpUser;
    protected $kpiCustomer;
    protected $kpiDebts;

    protected $kpiSaleItem;
    protected $kpiDebtsItem;
    protected $kpiStaffManager;
    protected $kpiStaffManagerItem;

    function __construct(KpiSetUpUserInterface $kpiSetUpUser,KpiSettingSaleItemInterface $kpiSaleItem,KpiSettingDebtsItemInterface $kpiDebtsItem,KpiSettingStaffManagerRepository $kpiSettingStaffManager,KpiSettingStaffManagerItemInterface $kpiSettingStaffManagerItem,KpiCustomerInterface $kpiCustomer,KpiDebtsInterface $kpiDebts)
    {
        $this->kpiSetUpUser = $kpiSetUpUser;
        $this->kpiCustomer = $kpiCustomer;
        $this->kpiDebts = $kpiDebts;
        $this->kpiSaleItem = $kpiSaleItem;
        $this->kpiDebtsItem = $kpiDebtsItem;
        $this->kpiStaffManager = $kpiSettingStaffManager;
        $this->kpiStaffManagerItem = $kpiSettingStaffManagerItem;
    }

    public function getList($filter=[])
    {
        return $this->kpiSetUpUser->getList($filter);

    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->kpiSetUpUser->getListPaginate($perPage,$filter);
    }


    public function create($data)
    {

        $arrSetUpUser =  [
            'name' => $data['name'],
            'user_id' => $data['user_assign'],
            'year' => Carbon::parse($data["year"] )->year,
            'sales_months' => $data['sales_months'],
            'sales_3_months' => $data['sales_3_months'],
            'sales_12_months' => $data['sales_12_months'],
            'sales_months_percent' => $data['sales_months_percent'],
            'sales_3_months_percent' => $data['sales_3_months_percent'],
            'sales_12_months_percent' => $data['sales_12_months_percent'],
            'current_sale_months' => $data['current_sale_months'],
            'current_sale_3_months' => $data['current_sale_3_months'],
            'current_sale_12_months' => $data['current_sale_12_months'],
            'current_sale_months_percent' => $data['current_sale_months_percent'],
            'current_sale_3_months_percent' => $data['current_sale_3_months_percent'],
            'current_sale_12_months_percent' => $data['current_sale_12_months_percent'],
            'debts_1_percent' => $data['debts_1_percent'],
            'debts_3_percent' => $data['debts_3_percent'],
            'debts_12_percent' => $data['debts_12_percent'],
            'sale_text' => $data['sale_text'],
            'current_sale_text' => $data['current_sale_text'],
            'debts_text' => $data['debts_text'],


        ];

        $kpiSetUpUser = $this->kpiSetUpUser->create($arrSetUpUser);

        if (!$kpiSetUpUser) {
            return $this->_result(false, 'Created failed');
        }
        $id = $kpiSetUpUser->id;

        //Add to staff manager
        $this->saveStaffManager($data,$id);

        //Add to sale conditions
        $this->saveSaleItems($data,$id);

        //Add to current sale conditions
        $this->saveCurrentSaleItems($data,$id);

        //Add to debts conditions
        $this->saveDebtsItems($data,$id);


        return $this->_result(true, 'Created successfully');
    }

    protected function saveStaffManager($data,$id){
        // 1. save with condition types
        $staffManagers = [];
        foreach (['kpi_condition_staff_month', 'kpi_condition_staff_square', 'kpi_condition_staff_year'] as $conditionKey) {
            if (!empty($data[$conditionKey])) {
                foreach ($data[$conditionKey] as $staffData) {
                    $type = null;
                    // declare type
                    if ($conditionKey === 'kpi_condition_staff_month') {
                        $type = '1months';
                    } elseif ($conditionKey === 'kpi_condition_staff_square') {
                        $type = '3months';
                    } elseif ($conditionKey === 'kpi_condition_staff_year') {
                        $type = '12months';
                    }

                    // add record to kpi_setting_staff_manager
                    $staffManagers[] = [
                        'user_id' => $staffData['user_id'],
                        'percent' => $staffData['percent'],
                        'kpi_setting_sale_id' => $id,
                        'type' => $type, // (1month, 3months, 12months)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Bulk Insert to table kpi_setting_staff_manager
        $this->kpiStaffManager->create($staffManagers);

        // 2.save to table kpi_setting_staff_manager_item
        $staffItems = [];
        foreach (['kpi_condition_staff_month', 'kpi_condition_staff_square', 'kpi_condition_staff_year'] as $conditionKey) {
            if (!empty($data[$conditionKey])) {
                foreach ($data[$conditionKey] as $staffData) {
                    $type = null;
                    if ($conditionKey === 'kpi_condition_staff_month') {
                        $type = '1months';
                    } elseif ($conditionKey === 'kpi_condition_staff_square') {
                        $type = '3months';
                    } elseif ($conditionKey === 'kpi_condition_staff_year') {
                        $type = '12months';
                    }

                    // find id in table kpi_setting_staff_manager_id
                    $staffManager =$this->kpiStaffManager->findFirstKpiStaff($staffData['user_id'],$id,$type);

                    if ($staffManager && !empty($staffData['staff_conditions'])) {
                        foreach ($staffData['staff_conditions'] as $condition) {
                            $staffItems[] = [
                                'number' => $condition['number'] ?? null,
                                'percentage' => $condition['percentage'],
                                'points' => $condition['points'],
                                'type' => $condition['type'],
                                'kpi_setting_staff_id' => $staffManager->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        // Bulk Insert to table kpi_setting_staff_manager_item
        $this->kpiStaffManagerItem->create($staffItems);
    }

    protected function  saveSaleItems($data,$id){
        $sale_months_conditions = $data['sale_months_conditions'];
        $sale_3months_conditions = $data['sale_3_months_conditions'];
        $sale_12months_conditions = $data['sale_12_months_conditions'];

        $arrSaleAll = array_merge($sale_months_conditions, $sale_3months_conditions, $sale_12months_conditions);
        $arrSale = [];
        foreach ($arrSaleAll as $key => $item){

            $arrSale[$key]["kpi_setting_sale_id"] = $id;
            $arrSale[$key]["points"] = $item['points'];
            $arrSale[$key]["min"] = $item['min'];
            $arrSale[$key]["max"] = $item['max'];
            $arrSale[$key]["percentage"] = $item['percentage'];
            $arrSale[$key]["type"] = $item['type'];

        }
        $this->kpiSaleItem->create($arrSale);
    }
    protected function  saveCurrentSaleItems($data,$id){
        $current_sale_months_conditions = $data['current_sale_months_conditions'];
        $current_sale_3_months_conditions = $data['current_sale_3_months_conditions'];
        $current_sale_12_months_conditions = $data['current_sale_12_months_conditions'];

        $arrSaleCurrentAll = array_merge($current_sale_months_conditions, $current_sale_3_months_conditions, $current_sale_12_months_conditions);
        $arrSaleCurrent = [];

        foreach ($arrSaleCurrentAll as $key => $item){
            $arrSaleCurrent[$key]["kpi_setting_sale_id"] = $id;
            $arrSaleCurrent[$key]["points"] = $item['points'];
            $arrSaleCurrent[$key]["min"] = $item['min'];
            $arrSaleCurrent[$key]["max"] = $item['max'];
            $arrSaleCurrent[$key]["percentage"] = $item['percentage'];
            $arrSaleCurrent[$key]["type"] = $item['type'];
            $arrSaleCurrent[$key]["type_kpi"] = 'current';

        }
        $this->kpiSaleItem->create($arrSaleCurrent);
    }

    protected function  saveDebtsItems($data,$id){
        $debts_months_conditions = $data['debts_months_conditions'];
        $debts_3_months_conditions = $data['debts_3_months_conditions'];
        $debts_12_months_conditions = $data['debts_12_months_conditions'];

        $arrDebtsAll = array_merge($debts_months_conditions, $debts_3_months_conditions, $debts_12_months_conditions);
        $arrDebts = [];
        foreach ($arrDebtsAll as $key => $item){
            $arrDebts[$key]["kpi_setting_sale_id"] = $id;
            $arrDebts[$key]["number"] = $item['number'];
            $arrDebts[$key]["points"] = $item['points'];
            $arrDebts[$key]["type"] = $item['type'];
            $arrDebts[$key]["percentage"] = $item['percentage'];
        }
        $this->kpiDebtsItem->create($arrDebts);
    }

    public function getByID($id)
    {
        $data = $this->kpiSetUpUser->getByID($id);

        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }
    public function getByUser($user_id,$year)
    {
        $data = $this->kpiSetUpUser->getByUser($user_id,$year);

        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }
    public function checkIdKpiCurrentYear($arrID)
    {
        $data = $this->kpiSetUpUser->checkIdKpiCurrentYear($arrID);

        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }




    public function update($id, $data)
    {
       // dd($data);
        DB::beginTransaction();
        try {

            // Lấy đối tượng KpiSettingSale cần cập nhật
            $kpiSettingSale = $this->kpiSetUpUser->getByID($id);

            // Cập nhật dữ liệu cơ bản
            $this->updateKpiSettings($id, $data);

            // Xử lý từng mối quan hệ
            $this->syncSaleItems($kpiSettingSale, $data);
            $this->syncDebtItems($kpiSettingSale, $data);
            $this->syncStaffManagers($kpiSettingSale, $data);

            DB::commit();
            return $this->_result(true, 'Updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->_result(false, 'Update failed: ' . $e->getMessage());
        }
    }

    private function updateKpiSettings($id, $data)
    {
        $this->kpiSetUpUser->update($id, [
            'name' => $data['name'],
            'user_id' => $data['user_assign'],
            'year' => Carbon::parse($data["year"] )->year,
            'sales_months' => $data['sales_months'],
            'sales_3_months' => $data['sales_3_months'],
            'sales_12_months' => $data['sales_12_months'],
            'sales_months_percent' => $data['sales_months_percent'],
            'sales_3_months_percent' => $data['sales_3_months_percent'],
            'sales_12_months_percent' => $data['sales_12_months_percent'],
            'current_sale_months' => $data['current_sale_months'],
            'current_sale_3_months' => $data['current_sale_3_months'],
            'current_sale_12_months' => $data['current_sale_12_months'],
            'current_sale_months_percent' => $data['current_sale_months_percent'],
            'current_sale_3_months_percent' => $data['current_sale_3_months_percent'],
            'current_sale_12_months_percent' => $data['current_sale_12_months_percent'],
            'debts_1_percent' => $data['debts_1_percent'],
            'debts_3_percent' => $data['debts_3_percent'],
            'debts_12_percent' => $data['debts_12_percent'],
            'sale_text' => $data['sale_text'],
            'current_sale_text' => $data['current_sale_text'],
            'debts_text' => $data['debts_text'],
        ]);
    }

    private function syncSaleItems($kpiSettingSale, $data)
    {
        $saleConditionsMap = [
            'sale_months_conditions' => 'sale',
            'sale_3_months_conditions' => 'sale',
            'sale_12_months_conditions' => 'sale',
            'current_sale_months_conditions' => 'current',
            'current_sale_3_months_conditions' => 'current',
            'current_sale_12_months_conditions' => 'current',
        ];

        $newItems = [];
        foreach ($saleConditionsMap as $key => $typeKpi) {
            if (isset($data[$key])) {
                foreach ($data[$key] as $item) {
                    $newItems[] = [
                        'id' => $item['id'] ?? null, // Nếu có ID, ta sẽ kiểm tra cập nhật
                        'min' => $item['min'] ?? null,
                        'max' => $item['max'] ?? null,
                        'percentage' => $item['percentage'] ?? null,
                        'points' => $item['points'] ?? null,
                        'type' => $item['type'] ?? null,
                        'type_kpi' => $typeKpi,
                    ];
                }
            }
        }

        // Lấy các ID hiện có
        $existingItems = $kpiSettingSale->saleItems()->get()->keyBy('id');

        foreach ($newItems as $item) {
            if (!empty($item['id']) && $existingItems->has($item['id'])) {
                // Nếu item tồn tại, thì cập nhật
                $existingItems[$item['id']]->update($item);
                $existingItems->forget($item['id']); // Loại bỏ item đã xử lý
            } else {
                // Nếu không tồn tại, thì tạo mới
                $kpiSettingSale->saleItems()->create($item);
            }
        }

        // Xóa các item không còn trong dữ liệu mới
        $existingItems->each->delete();
    }

    private function syncDebtItems($kpiSettingSale, $data)
    {
        $debtKeys = ['debts_months_conditions', 'debts_3_months_conditions', 'debts_12_months_conditions'];

        $newItems = [];
        foreach ($debtKeys as $key) {
            if (isset($data[$key])) {
                foreach ($data[$key] as $item) {
                    $newItems[] = [
                        'id' => $item['id'] ?? null,
                        'number' => $item['number'] ?? null,
                        'percentage' => $item['percentage'] ?? null,
                        'points' => $item['points'] ?? null,
                        'type' => $item['type'] ?? null,
                    ];
                }
            }
        }

        $existingItems = $kpiSettingSale->debtItems()->get()->keyBy('id');

        foreach ($newItems as $item) {
            if (!empty($item['id']) && $existingItems->has($item['id'])) {
                $existingItems[$item['id']]->update($item);
                $existingItems->forget($item['id']);
            } else {
                $kpiSettingSale->debtItems()->create($item);
            }
        }

        $existingItems->each->delete();
    }

    private function syncStaffManagers($kpiSettingSale, $data)
    {
        $staffManagerGroups = [
            '1months' => $data['kpi_condition_staff_month'] ?? [],
            '3months' => $data['kpi_condition_staff_square'] ?? [],
            '12months' => $data['kpi_condition_staff_year'] ?? [],
        ];

        // Tạo collection key-based để tối ưu tìm kiếm
        $existingManagers = $kpiSettingSale->staffManagers()
            ->with('staffConditions')
            ->get()
            ->keyBy(function ($manager) {
                return $manager->user_id . '|' . $manager->type;
            });

        foreach ($staffManagerGroups as $type => $managers) {
            foreach ($managers as $manager) {
                $key = ($manager['user_id'] ?? null) . '|' . $type;

                if ($existingManagers->has($key)) {
                    // Nếu manager tồn tại, cập nhật và sync điều kiện
                    $existingManager = $existingManagers->get($key);
                    $existingManager->update([
                        'percent' => $manager['percent'] ?? null,
                    ]);
                    $this->syncStaffConditions($existingManager, $manager['staff_conditions']);
                    $existingManagers->forget($key);
                } else {
                    // Tạo mới nếu không tồn tại
                    $newManager = $kpiSettingSale->staffManagers()->create([
                        'user_id' => $manager['user_id'] ?? null,
                        'percent' => $manager['percent'] ?? null,
                        'type' => $type,
                    ]);
                    $this->syncStaffConditions($newManager, $manager['staff_conditions']);
                }
            }
        }

        // Xóa các managers còn sót lại
        $existingManagers->each(function ($manager) {
            $manager->staffConditions()->delete();
            $manager->delete();
        });
    }

    private function syncStaffConditions($staffManager, $conditions)
    {
        $existingConditions = $staffManager->staffConditions()->get()->keyBy('id');

        foreach ($conditions as $condition) {
            if (!empty($condition['id']) && $existingConditions->has($condition['id'])) {
                $existingConditions[$condition['id']]->update($condition);
                $existingConditions->forget($condition['id']); // Xóa khỏi danh sách còn sót
            } else {
                $staffManager->staffConditions()->create($condition);
            }
        }
        $existingConditions->each(function ($condition) {
            $condition->delete();
        });
    }


    public function destroyByIDs($ids)
    {
        if(empty($ids)) return $this->_result(false, 'Delete failed!');
        $kpiSettingSale =  $this->kpiSetUpUser->getByID($ids[0]);
        $kpiSettingSale->saleItems()->delete();
        $kpiSettingSale->debtItems()->delete();
        $kpiSettingSale->staffManagers()->each(function ($manager) {
            $manager->staffConditions()->delete();
            $manager->delete();
        });
        $check = $this->kpiSetUpUser->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
