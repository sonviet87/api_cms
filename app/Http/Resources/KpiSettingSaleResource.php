<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KpiSettingSaleResource extends JsonResource
{
    public function toArray($request)
    {

        $saleItems = $this->saleItems ->sortBy('min')->groupBy(['type_kpi', 'type']);
        $debtItems = $this->debtItems->sortBy('number')->groupBy('type');
        $staffManagers = $this->staffManagers->groupBy('type');

        $date = Carbon::create($this->year, 1, 1, 0, 0, 0, 'UTC');


        return [
            'name' => $this->name,
            'year' => $date,
            'users' => $this->user->subordinates()->get()->toArray(),
            'user_assign' => $this->user_id,
            'sales_months' => $this->sales_months,
            'sales_3_months' => $this->sales_3_months,
            'sales_12_months' => $this->sales_12_months,
            'sale_text' => $this->sale_text,
            'sales_months_percent' => $this->sales_months_percent,
            'sales_3_months_percent' => $this->sales_3_months_percent,
            'sales_12_months_percent' => $this->sales_12_months_percent,
            'current_sale_months' => $this->current_sale_months,
            'current_sale_3_months' => $this->current_sale_3_months,
            'current_sale_12_months' => $this->current_sale_12_months,
            'current_sale_text' => $this->current_sale_text,
            'current_sale_months_percent' => $this->current_sale_months_percent,
            'current_sale_3_months_percent' => $this->current_sale_3_months_percent,
            'current_sale_12_months_percent' => $this->current_sale_12_months_percent,
            'debts_text' => $this->debts_text,
            'debts_1_percent' => $this->debts_1_percent,
            'debts_3_percent' => $this->debts_3_percent,
            'debts_12_percent' => $this->debts_12_percent,

            'sale_months_conditions' => $saleItems['sale']['1months'] ?? [],
            'sale_3_months_conditions' => $saleItems['sale']['3months'] ?? [],
            'sale_12_months_conditions' => $saleItems['sale']['12months'] ?? [],
            'current_sale_months_conditions' => $saleItems['current']['1months'] ?? [],
            'current_sale_3_months_conditions' => $saleItems['current']['3months'] ?? [],
            'current_sale_12_months_conditions' => $saleItems['current']['12months'] ?? [],
            'debts_months_conditions' => $debtItems['1months'] ?? [],
            'debts_3_months_conditions' => $debtItems['3months'] ?? [],
            'debts_12_months_conditions' => $debtItems['12months'] ?? [],
            'kpi_condition_staff_month' => $staffManagers->count() >0 ? $this->transformStaffManagers($staffManagers['1months'] ?? []) : [],
            'kpi_condition_staff_square' => $staffManagers->count() >0 ?  $this->transformStaffManagers($staffManagers['3months'] ?? []) : [],
            'kpi_condition_staff_year' =>  $staffManagers->count() >0 ? $this->transformStaffManagers($staffManagers['12months'] ?? []) : [],
        ];
    }



    private function transformStaffManagers($staffManagers)
    {
        return $staffManagers->map(function ($manager) {
            return [
                'user_id' => $manager->user_id,
                'percent' => $manager->percent,
                'staff_conditions' => $manager->staffConditions
                    ->sortBy('number')
                    ->map(function ($condition) {
                        return [
                            'number' => $condition->number,
                            'percentage' => $condition->percentage,
                            'type' => $condition->type,
                            'points' => $condition->points,
                        ];
                    })
                    ->values(),
            ];
        });
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}
