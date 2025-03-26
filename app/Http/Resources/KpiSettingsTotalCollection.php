<?php

namespace App\Http\Resources;


use App\Repositories\SysKpiRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;


class KpiSettingsTotalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->collection->transform(function ($item) {
            return [
                'id' => $item->id,
                'min_percentage' => $item->min,
                'max_percentage' => $item->max,
                'percentage' => $item->percentage,
                'type' => $item->type,
                'points' => $item->points,
                'bonus' => $item->bonus,
                'type_kpi' => $item->type_kpi,
                'name' => $item->name,
            ];
        })->toArray();


        $grouped = [];
        foreach ($data as $item) {
            $typeKpi = $item['type_kpi'] ?? 'sale';
            $type = $item['type'] ?? '1months';


            if (!isset($grouped[$typeKpi])) {
                $grouped[$typeKpi] = [];
            }

            if (!isset($grouped[$typeKpi][$type])) {
                $grouped[$typeKpi][$type] = [];
            }

            $grouped[$typeKpi][$type][] = $item;
        }

        return $grouped;
    }

    /**
     * Additional data to send with the response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        $sysKpiRepository = app(SysKpiRepository::class);
        $kpiCompanyData = $sysKpiRepository->getByID(1);

        return [
            'status' => true,
            'kpi_company' => $kpiCompanyData->kpi_company,
        ];
    }
}

