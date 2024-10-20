<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {

    // for creating admin user
    User::create([
      'name' => 'Admin User',
      'email' => 'admin@gmail.com',
      'password' => Hash::make('12345678'),
      'user_type' => 'admin',
    ]);


    // for creating regular user
    User::create([
      'name' => 'Sahed',
      'email' => 'user@gmail.com',
      'password' => Hash::make('12345678'),
      'user_type' => 'user',
    ]);
  }
}