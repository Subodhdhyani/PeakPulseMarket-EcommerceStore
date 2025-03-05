<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tblfront_content;

class tblfront_contents extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $front = new tblfront_content;
        $front->email = 'info@peakpulsemarket.com';
        $front->number = 9999999999;
        $front->save();
    }
}
