<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
            'name' => 'Workstation Dell Precision 3640',
            'descriptions' => 'Workstation Dell Precision Tower 3630
 - CPU: Intel Xeon W-1250 (3.30GHz Upto 4.70GHz, 6 Cores 12 Threads, 12MB Cache)
 - RAM: 16GB (2 x 8 GB) DDR4 2666 MHz or 2933MHzUDIMM Non-ECC Memory
 -  Ổ cứng: 1TB 7200rpm SATA 3.5"""" HDD / 8x DVD+/-RW 9.5mm ODD / Intel Ethernet Connection I219-LM 10/100/1000/
 - Nvidia Quadro P1000, 4GB, 4 mDP to DP adapter/ Ubuntu Linux 18.04/
 -  Dell optical Mouse & Keyboard /
-  3Yr Prosupport ',
            'tax_percent' => '10',

        ]);

        \App\Models\Category::create([
            'name' => 'Windows 10 Pro 64Bit Eng Intl 1pk DSP OEI DVD',
            'descriptions' => 'Windows 10 Pro 64Bit Eng Intl 1pk DSP OEI DVD ',
            'tax_percent' => '10',

        ]);

    }
}
