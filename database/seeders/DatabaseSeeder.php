<?php

namespace Database\Seeders;

use App\Models\Mantra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['title' => 'राधे राधे', 'transliteration' => 'Radhe Radhe', 'count_label' => 'Most loved'],
            ['title' => 'श्री राधे', 'transliteration' => 'Shri Radhe', 'count_label' => '108 jap'],
            ['title' => 'ॐ नमो भगवते वासुदेवाय', 'transliteration' => 'Om Namo Bhagavate Vasudevaya', 'count_label' => '108 jap'],
            ['title' => 'हरे कृष्ण महामंत्र', 'transliteration' => 'Hare Krishna Mahamantra', 'count_label' => '108 jap'],
            ['title' => 'राधा जी के 28 नाम', 'transliteration' => '28 names of Radha', 'count_label' => '28 names'],
        ] as $mantra) {
            Mantra::updateOrCreate(['user_id' => null, 'title' => $mantra['title']], $mantra);
        }
    }
}
