<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chama;

class ChamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chama = Chama::first();

    Borrower::query()->update([
        'chama_id' => $chama->id
    ]);
    }
}
