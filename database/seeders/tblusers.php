<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\tbluser;

class tblusers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new tbluser;
        $admin->name = 'admin';
        $admin->role = 'admin';
        $admin->email = 'admin@peakpulsemarket.com';
        $admin->phone_number = 9898989898;
        $admin->password = Hash::make('admin@12345');
        $admin->save();
    }
}
