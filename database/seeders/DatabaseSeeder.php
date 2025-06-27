<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // User::factory(10)->create();

    User::create([
      'name' => 'Admin',
      'email' => 'admin@gmail.com',
      'phone_number' => '+6289666020017',
      'password' => bcrypt('admin123'),
    ]);

    Product::create([
      'name' => 'Laptop Asus ROG Strix',
      'category' => 'Elektronik',
      'price' => 20000000,
      'stock' => 15,
    ]);

    Product::create([
      'name' => 'Smartphone Samsung Galaxy S24',
      'category' => 'Elektronik',
      'price' => 15000000,
      'stock' => 25,
    ]);

    Product::create([
      'name' => 'Smart TV LG 55 inch',
      'category' => 'Elektronik',
      'price' => 8000000,
      'stock' => 10,
    ]);

    Product::create([
      'name' => 'Headphone Bluetooth Sony WH-1000XM5',
      'category' => 'Elektronik',
      'price' => 4500000,
      'stock' => 30,
    ]);

    Product::create([
      'name' => 'Smartwatch Apple Watch Series 9',
      'category' => 'Elektronik',
      'price' => 7000000,
      'stock' => 20,
    ]);

    Product::create([
      'name' => 'Kemeja Batik Lengan Panjang',
      'category' => 'Pakaian',
      'price' => 250000,
      'stock' => 50,
    ]);

    Product::create([
      'name' => 'Celana Jeans Slim Fit',
      'category' => 'Pakaian',
      'price' => 350000,
      'stock' => 40,
    ]);

    Product::create([
      'name' => 'Gaun Pesta Malam',
      'category' => 'Pakaian',
      'price' => 750000,
      'stock' => 20,
    ]);

    Product::create([
      'name' => 'Jaket Kulit Pria',
      'category' => 'Pakaian',
      'price' => 900000,
      'stock' => 18,
    ]);

    Product::create([
      'name' => 'Rok Plisket Wanita',
      'category' => 'Pakaian',
      'price' => 180000,
      'stock' => 65,
    ]);

    Product::create([
      'name' => 'Novel Fiksi Ilmiah: Dune',
      'category' => 'Buku',
      'price' => 120000,
      'stock' => 30,
    ]);

    Product::create([
      'name' => 'Buku Resep Masakan Nusantara',
      'category' => 'Buku',
      'price' => 90000,
      'stock' => 60,
    ]);

    Product::create([
      'name' => 'Komik Petualangan Detektif Conan Vol. 100',
      'category' => 'Buku',
      'price' => 45000,
      'stock' => 80,
    ]);

    Product::create([
      'name' => 'Buku Self-Improvement: Atomic Habits',
      'category' => 'Buku',
      'price' => 110000,
      'stock' => 55,
    ]);

    Product::create([
      'name' => 'Ensiklopedia Hewan Dunia',
      'category' => 'Buku',
      'price' => 300000,
      'stock' => 12,
    ]);
  }
}
