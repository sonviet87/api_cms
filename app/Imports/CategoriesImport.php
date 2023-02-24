<?php

namespace App\Imports;


use App\Models\Category;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoriesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Category|null
     */
    public function model(array $row)
    {
        //dd($row);
        return new Category([
            'name'     => $row['name'],
            'descriptions'    => $row['descriptions'],
            'tax_percent' => $row['tax_percent'],
            'created_at' => Carbon::today()
        ]);
    }
}
