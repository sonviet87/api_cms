<?php

namespace App\Services;


use App\Interfaces\KpiDebtsInterface;
use App\Interfaces\KpiSettingDebtsItemInterface;
use App\Interfaces\KpiSettingSaleItemInterface;
use App\Interfaces\KpiSettingStaffManagerItemInterface;
use App\Interfaces\KpiSettingTechnicalInterface;
use App\Interfaces\KpiSetUpUserInterface;
use App\Repositories\KpiSettingStaffManagerRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KpiSettingTechnicalrService extends BaseService
{

    protected $kpiSettingTechnical;
    protected $kpiSettingStaffManager;
    protected $kpiSettingStaffManagerItem;


    function __construct(KpiSettingTechnicalInterface $kpiSettingTechnical, KpiSetUpUserInterface $kpiSetUpUser,KpiSettingSaleItemInterface $kpiSaleItem,KpiSettingDebtsItemInterface $kpiDebtsItem,KpiSettingStaffManagerRepository $kpiSettingStaffManager,KpiSettingStaffManagerItemInterface $kpiSettingStaffManagerItem,KpiDebtsInterface $kpiDebts)
    {
        $this->kpiSettingTechnical = $kpiSettingTechnical;
        $this->kpiSettingStaffManager = $kpiSettingStaffManager;
        $this->kpiSettingStaffManagerItem = $kpiSettingStaffManagerItem;

    }

    public function getList($filter=[])
    {
        return $this->kpiSettingTechnical->getList($filter);

    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->kpiSettingTechnical->getListPaginate($perPage,$filter);
    }


    public function create($data)
    {

        $arrSetting =  [
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'year' => Carbon::parse($data["year"] )->year,
            'certificate_conditions' => $data['certificate_conditions'],
            'project_conditions' => $data['project_conditions'],
            'review_conditions' => $data['review_conditions'],
            'review_percent' => $data['review_percent'],
            'project_percent' => $data['project_percent'],
            'certificate_percent' => $data['certificate_percent'],

        ];

        $kpiSetting = $this->kpiSettingTechnical->create($arrSetting);

        if (!$kpiSetting) {
            return $this->_result(false, 'Created failed');
        }
        $id = $kpiSetting->id;

        //Add to staff manager
        $this->saveStaffManager($data,$id);

        return $this->_result(true, 'Created successfully');
    }

    protected function saveStaffManager($data,$id){
        // 1. save with condition types
        $staffManagers = [];
        foreach (['kpi_condition_staff_year'] as $conditionKey) {
            if (!empty($data[$conditionKey])) {
                foreach ($data[$conditionKey] as $staffData) {
                    // add record to kpi_setting_staff_manager
                    $staffManagers[] = [
                        'user_id' => $staffData['user_id'],
                        'kpi_setting_sale_id' => $id,
                        'type' => '12months',
                        'percent' => $staffData['percent'],
                        'created_at' => now(),
                        'updated_at' => now(),
                        'kpi_type' =>'technical'
                    ];
                }
            }
        }

        // Bulk Insert to table kpi_setting_staff_manager
        $this->kpiSettingStaffManager->create($staffManagers);

        // 2.save to table kpi_setting_staff_manager_item
        /*$staffItems = [];
        foreach (['kpi_condition_staff_year'] as $conditionKey) {
            if (!empty($data[$conditionKey])) {
                foreach ($data[$conditionKey] as $staffData) {

                    // find id in table kpi_setting_staff_manager_id
                    $staffManager =$this->kpiSettingStaffManager->findFirstKpiStaff($staffData['user_id'],$id,'12months','technical');

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
       // dd($staffItems);
        // Bulk Insert to table kpi_setting_staff_manager_item
        $this->kpiSettingStaffManagerItem->create($staffItems);*/
    }



    public function getByID($id)
    {
        $data = $this->kpiSettingTechnical->getByID($id);

        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }


    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            // Lấy đối tượng KpiSettingSale cần cập nhật
            $kpiSettingTechnica = $this->kpiSettingTechnical->getByID($id);
            $arrSetting =  [
                'name' => $data['name'],
                'user_id' => $data['user_id'],
                'year' => Carbon::parse($data["year"] )->year,
                'certificate_conditions' => $data['certificate_conditions'],
                'project_conditions' => $data['project_conditions'],
                'review_conditions' => $data['review_conditions'],
                'review_percent' => $data['review_percent'],
                'project_percent' => $data['project_percent'],
                'certificate_percent' => $data['certificate_percent'],

            ];
            $this->kpiSettingTechnical->update($id,$arrSetting);
            $this->syncStaffManagers($kpiSettingTechnica, $data);


            DB::commit();
            return $this->_result(true, 'Updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->_result(false, 'Update failed: ' . $e->getMessage());
        }
    }


    private function syncStaffManagers($kpiSettingTechnical, $data)
    {
        $existingStaffManagers = $kpiSettingTechnical->staffManagers()->pluck('user_id')->toArray();
        $newStaffManagers = isset($data['kpi_condition_staff_year']) ? $data['kpi_condition_staff_year'] : [];

        $newStaffManagerIds = array_column($newStaffManagers, 'user_id');

        // Xóa những staffManagers không có trong dữ liệu mới
        $kpiSettingTechnical->staffManagers()->whereNotIn('user_id', $newStaffManagerIds)->delete();

        foreach ($newStaffManagers as $staffManager) {
            if (isset($staffManager['user_id']) && in_array($staffManager['user_id'], $existingStaffManagers)) {
                // Cập nhật staffManager nếu đã tồn tại
                $kpiSettingTechnical->staffManagers()->where('user_id', $staffManager['user_id'])->update([
                    'percent' => $staffManager['percent'],
                    'type' => $staffManager['type'] ?? '12months',
                    'kpi_type' => $staffManager['kpi_type'] ?? 'technical',
                ]);
            } else {
                // Thêm mới staffManager nếu chưa có
                $kpiSettingTechnical->staffManagers()->create([
                    'user_id' => $staffManager['user_id'],
                    'percent' => $staffManager['percent'],
                    'type' => $staffManager['type'] ?? '12months',
                    'kpi_type' => $staffManager['kpi_type'] ?? 'technical',
                ]);
            }
        }
       /* $staffManagerGroups = [
            '12months' => $data['kpi_condition_staff_year'] ?? [],
        ];

        // Lấy danh sách manager hiện có và cấu trúc lại để dễ truy cập
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

                    $existingManager = $existingManagers->get($key);

                    $this->syncStaffConditions($existingManager, $manager['staff_conditions']);
                    $existingManagers->forget($key);
                } else {

                    $newManager = $kpiSettingSale->staffManagers()->create([
                        'user_id' => $manager['user_id'] ?? null,
                        'type' => $type,
                    ]);
                    $this->syncStaffConditions($newManager, $manager['staff_conditions']);
                }
            }
        }

        // Xóa những manager còn sót lại
        $existingManagers->each(function ($manager) {
            $manager->staffConditions()->delete();
            $manager->delete();
        });*/
    }

    private function syncStaffConditions($staffManager, $conditions)
    {
        $existingConditions = $staffManager->staffConditions()->get()->keyBy('id');

        foreach ($conditions as $condition) {
            if (!empty($condition['id']) && $existingConditions->has($condition['id'])) {
                $existingConditions[$condition['id']]->update($condition);
                $existingConditions->forget($condition['id']);
            } else {
                $staffManager->staffConditions()->create($condition);
            }
        }

        $existingConditions->each->delete();
    }


    public function destroyByIDs($ids)
    {
        if(empty($ids)) return $this->_result(false, 'Delete failed!');
        $kpiSettingSale =  $this->kpiSettingTechnical->getByID($ids[0]);

        $kpiSettingSale->staffManagers()->each(function ($manager) {
            $manager->staffConditions()->delete();
            $manager->delete();
        });
        $check = $this->kpiSettingTechnical->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
