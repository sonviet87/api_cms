<?php

namespace App\Services;


use App\Interfaces\KpiSettingsInterface;
use App\Interfaces\KpiSettingTotalInterface;
use App\Interfaces\SysKpiInterface;
use Illuminate\Support\Arr;


class KpiSettingsTotalService extends BaseService
{

    protected $kpiSettingsTotal;
    protected $sysKpi;

    function __construct(KpiSettingTotalInterface $kpiSettingsTotal,SysKpiInterface $sysKpi)
    {
        $this->kpiSettingsTotal = $kpiSettingsTotal;
        $this->sysKpi = $sysKpi;
    }

    public function getList()
    {
        return $this->kpiSettingsTotal->getList();

    }

    public function create($data)
    {
        $rs = $this->kpiSettingsTotal->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->kpiSettingsTotal->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }

        return $this->_result(true, '', $data);
    }


    public function update($id, $data)
    {

        $kpi_company = $data['kpi_company'] ?? null;
        $this->sysKpi->update(1, ['kpi_company' => $kpi_company]);

        $mergedKpiData = [];
        foreach ($data as $kpiType => $kpiData) {
            if ($kpiType === 'kpi_company') {
                continue;
            }

            foreach (['1months', '3months', '12months'] as $timePeriod) {
                if (!empty($kpiData[$timePeriod])) {
                    foreach ($kpiData[$timePeriod] as $item) {
                        $item['type'] = $timePeriod;
                        $item['type_kpi'] = $kpiType;
                        $mergedKpiData[] = $item;
                    }
                }
            }
        }

        $currentRecords = $this->getList()->keyBy('id');
        $newRecords = [];
        $updatedIds = [];

        foreach ($mergedKpiData as $item) {
            $itemData = [
                "type_kpi" => $item['type_kpi'],
                "points" => $item['points'] ?? null,
                "bonus" => $item['bonus'] ?? null,
                "name" => $item['name'] ?? null,
                "max" => $item['max_percentage'] ?? null,
                "min" => $item['min_percentage'] ?? null,
                "percentage" => $item['percentage'] ?? null,
                "type" => $item['type'],
            ];

            if (isset($item['id']) && isset($currentRecords[$item['id']])) {
                $currentRecord = $currentRecords[$item['id']]->toArray();

                if (array_diff_assoc($itemData, $currentRecord)) {
                    $this->kpiSettingsTotal->update($item['id'], $itemData);
                }
                $updatedIds[] = $item['id'];
            } else {
                $newRecords[] = $itemData;
            }
        }

        $currentIds = $currentRecords->keys()->toArray();
        $idsToDelete = array_diff($currentIds, $updatedIds);
        if (!empty($idsToDelete)) {
            $this->kpiSettingsTotal->destroy($idsToDelete);
        }

        if (!empty($newRecords)) {
            $this->kpiSettingsTotal->insert($newRecords);
        }

        return $this->_result(true, 'Updated successfully');
    }


    public function destroyByIDs($ids)
    {
        $check = $this->kpiSettingsTotal->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
