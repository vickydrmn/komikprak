<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artikel_komik;

class ArtikelKomikFactory extends Factory
{
    protected $model = Artikel_komik::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->words(3, true),
            'genre' => $this->faker->word,
            'autor' => $this->faker->name,
            'tanggal_update' => $this->faker->date,
            'tanggal_rilis' => $this->faker->date,
            'deskripsi' => $this->faker->paragraph,
            'foto' => 'noimage.png', // Default jika tidak diuji dengan file
        ];
    }
}
