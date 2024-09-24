<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        
        User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($studentRole);
        User::create([
            'name' => 'Student User',
            'email' => 'student1@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($studentRole);
        User::create([
            'name' => 'Student User',
            'email' => 'student2@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($studentRole);
        User::create([
            'name' => 'Student User',
            'email' => 'student3@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($studentRole);
    }
}
