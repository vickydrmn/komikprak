<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel_komik;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Illuminate\Support\Facades\Log;


class ArtikelController extends Controller
{
    use ValidatesRequests;
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Lakukan query dan terapkan filter pencarian jika ada
        $artikel = Artikel_komik::query();

        if ($search) {
            $artikel->where('nama', 'like', '%' . $search . '%');
        }

        // Setelah filter, lakukan paginasi
        $artikel = $artikel->paginate(3);

        // Kembalikan ke view
        return view('artikel.index', compact('artikel'));
    }


    public function create()
    {
        return view('artikel.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'genre' => 'required',
            'autor' => 'required',
            'tanggal_update' => 'required',
            'tanggal_rilis' => 'required',
            'deskripsi' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp',
        ]);

        $foto = $request->file('foto');

        if ($foto && $foto->isValid()) {
            Log::info('File foto diterima: ' . $foto->getClientOriginalName());

            // Buat nama file yang unik
            $uniqueFileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();

            // Simpan file dengan nama yang unik
            $foto->storeAs('foto', $uniqueFileName, 'public');

            Log::info('File foto berhasil disimpan di: ' . storage_path('app/public/foto/' . $uniqueFileName));

            Artikel_komik::create([
                'nama' => $request->nama,
                'genre' => $request->genre,
                'autor' => $request->autor,
                'tanggal_update' => $request->tanggal_update,
                'tanggal_rilis' => $request->tanggal_rilis,
                'deskripsi' => $request->deskripsi,
                'foto' => $uniqueFileName, // Simpan nama file yang unik
            ]);

            return redirect()->route('artikel.index')->with('success', 'Komik berhasil ditambahkan');
        } else {
            return back()->withErrors(['foto' => 'File upload tidak valid.']);
        }
    }

    public function edit(Artikel_komik $artikel)
    {
        return view('artikel.edit', compact('artikel'));
    }

    public function update(Request $request, Artikel_komik $artikel)
    {
        $this->validate($request, [
            'nama' => 'required',
            'genre' => 'required',
            'autor' => 'required',
            'tanggal_update' => 'required',
            'tanggal_rilis' => 'required',
            'deskripsi' => 'required',
        ]);

        $artikel->nama = $request->nama;
        $artikel->genre = $request->genre;
        $artikel->autor = $request->autor;
        $artikel->tanggal_update = $request->tanggal_update;
        $artikel->tanggal_rilis = $request->tanggal_rilis;
        $artikel->deskripsi = $request->deskripsi;

        if ($request->file('foto')) {
            // Hapus foto yang lama
            Storage::disk('public')->delete('foto/' . $artikel->foto);
            $foto = $request->file('foto');

            // Buat nama file yang unik
            $uniqueFileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            // Simpan file dengan nama yang unik
            $foto->storeAs('foto', $uniqueFileName, 'public');
            $artikel->foto = $uniqueFileName; // Simpan nama file yang unik
        }

        $artikel->save();
        return redirect()->route('artikel.index')->with('success', 'Komik berhasil diupdate');
    }

    public function destroy(Artikel_komik $artikel)
    {
        if ($artikel->foto  !== "noimage.png") {
            Storage::disk('public')->delete('foto/' . $artikel->foto);
        }
        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Buku komik berhasil dihapus');
    }
}
