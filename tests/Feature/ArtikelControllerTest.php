<?php

namespace Tests\Feature;

use App\Models\Artikel_komik;
use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArtikelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_search_for_an_article_by_name()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Membuat artikel contoh
        Artikel_komik::create([
            'nama' => 'Komik A',
            'genre' => 'Action',
            'autor' => 'Penulis A',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi Komik A',
            'foto' => 'example.jpg'
        ]);

        // Melakukan pencarian artikel berdasarkan nama
        $response = $this->get('/artikel?search=Komik A');

        // Memastikan responsenya sukses dan artikel ditemukan
        $response->assertStatus(200);
        $response->assertSee('Komik A');
    }


    public function test_it_shows_an_error_if_no_photo_is_uploaded()
    {
        // Membuat pengguna uji
        $user = User::factory()->create(); // Membuat satu pengguna

        // Autentikasi pengguna
        $this->actingAs($user); // Menggunakan objek pengguna tunggal

        // Data tanpa foto
        $data = [
            'nama' => 'Komik Tanpa Foto',
            'genre' => 'Fantasy',
            'autor' => 'Penulis Tanpa Foto',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi komik tanpa foto'
        ];

        // Melakukan POST request tanpa mengupload foto
        $response = $this->post('/artikel', $data);

        // Memastikan bahwa sistem mengembalikan error
        $response->assertSessionHasErrors('foto');
    }

    public function test_it_can_update_an_existing_article()
    {
        // Membuat pengguna uji
        $user = User::factory()->create(); // Membuat satu pengguna

        // Autentikasi pengguna
        $this->actingAs($user); // Menggunakan objek pengguna tunggal

        // Membuat artikel contoh untuk diuji
        $artikel = Artikel_komik::create([
            'nama' => 'Komik Lama',
            'genre' => 'Drama',
            'autor' => 'Penulis Lama',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi komik lama',
            'foto' => 'old_foto.jpg'
        ]);

        // Data baru untuk artikel
        $data = [
            'nama' => 'Komik Baru Diperbarui',
            'genre' => 'Sci-Fi',
            'autor' => 'Penulis Baru Diperbarui',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi komik baru setelah update'
        ];

        // Melakukan PUT request untuk memperbarui artikel
        $response = $this->put(route('artikel.update', $artikel), $data);

        // Memastikan responsenya sukses
        $response->assertRedirect(route('artikel.index'));
        $response->assertSessionHas('success', 'Komik berhasil diupdate');

        // Memastikan artikel terupdate di database
        $this->assertDatabaseHas('artikel_komiks', [
            'nama' => 'Komik Baru Diperbarui',
            'genre' => 'Sci-Fi',
            'autor' => 'Penulis Baru Diperbarui',
        ]);
    }

    public function test_it_can_delete_an_article()
    {
        // Membuat pengguna uji
        $user = User::factory()->create(); // Membuat satu pengguna

        // Autentikasi pengguna
        $this->actingAs($user); // Menggunakan objek pengguna tunggal

        // Membuat artikel contoh
        $artikel = Artikel_komik::create([
            'nama' => 'Komik Dihapus',
            'genre' => 'Mystery',
            'autor' => 'Penulis Dihapus',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi komik yang akan dihapus',
            'foto' => 'foto_dihapus.jpg'
        ]);

        // Menyimpan foto di storage untuk memastikan bisa dihapus
        Storage::fake('public');

        // Melakukan DELETE request untuk menghapus artikel
        $response = $this->delete(route('artikel.destroy', $artikel));

        // Memastikan responsenya sukses dan halaman mengarahkan ke index artikel
        $response->assertRedirect(route('artikel.index'));
        $response->assertSessionHas('success', 'Buku komik berhasil dihapus');

        // Memastikan data artikel terhapus dari database
        $this->assertDatabaseMissing('artikel_komiks', [
            'nama' => 'Komik Dihapus'
        ]);

        // Memastikan foto juga terhapus dari storage
        $this->assertFalse(Storage::disk('public')->exists('foto/' . $artikel->foto));
    }

    public function test_it_does_not_delete_default_image_when_no_image_is_uploaded()
    {
        // Membuat artikel dengan foto default
        $artikel = Artikel_komik::create([
            'nama' => 'Komik Default Image',
            'genre' => 'Romance',
            'autor' => 'Penulis Default',
            'tanggal_update' => now(),
            'tanggal_rilis' => now(),
            'deskripsi' => 'Deskripsi komik dengan gambar default',
            'foto' => 'noimage.png'
        ]);

        // Menghapus artikel yang memiliki foto default
        $response = $this->delete(route('artikel.destroy', $artikel));

        // Memastikan bahwa foto tidak terhapus
        $this->assertTrue(Storage::disk('public')->exists('foto/noimage.png'));
    }
}
