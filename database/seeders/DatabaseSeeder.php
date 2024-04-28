<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produk;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // $table->string('title');
        // $table->text('description');
        // $table->integer('stock');
        // $table->bigInteger('price');
        // $table->string('image')->nullable();
        // $table->date('expired');

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin123',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        // Produk::create([
        //     'title' => 'Pisang Goreng',
        //     'description' => 'Pisang goreng terenak yang pernah ada, anda harus mencoba sebanyak mungkin agar hidup anda jauh lebih baik di kehidupan ini maupun di kehidupan selanjutnya',
        //     'stock' => 199,
        //     'price' => 15000,
        //     'image' => null,
        //     'expired' => '2025-09-12',
        // ]);
    }
}
