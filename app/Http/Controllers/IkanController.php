<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use Illuminate\Http\Request;
// pakai Http untuk kirim file ke Cloudinary
use Illuminate\Support\Facades\Http;

class IkanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $ikans = Ikan::query()
            ->when($q, function ($query, $q) {
                $query->where('nama', 'like', '%' . $q . '%')
                      ->orWhere('kategori', 'like', '%' . $q . '%');
            })
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        return view('admin.ikan.index', compact('ikans'));
    }

    public function create()
    {
        return view('admin.ikan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'nullable|string|max:100',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            // boleh kosong, maksimal 5 MB (5120 KB)
            'gambar'    => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        // upload ke Cloudinary kalau ada file
        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');

                $response = Http::asMultipart()->post(
                    'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                    [
                        [
                            'name'     => 'file',
                            'contents' => fopen($file->getRealPath(), 'r'),
                            'filename' => $file->getClientOriginalName(),
                        ],
                        [
                            'name'     => 'upload_preset',
                            'contents' => env('CLOUDINARY_UPLOAD_PRESET'),
                        ],
                    ]
                );

                $result = $response->json();

                if (isset($result['secure_url'])) {
                    // simpan URL gambar Cloudinary di kolom `gambar`
                    $data['gambar'] = $result['secure_url'];
                } else {
                    return back()
                        ->withErrors([
                            'gambar' => 'Cloudinary upload error: ' . ($result['error']['message'] ?? 'Unknown error'),
                        ])
                        ->withInput();
                }
            } catch (\Exception $e) {
                return back()
                    ->withErrors([
                        'gambar' => 'Cloudinary error: ' . $e->getMessage(),
                    ])
                    ->withInput();
            }
        }

        Ikan::create($data);

        return redirect()->route('admin.ikan.index')
            ->with('success', 'Data ikan berhasil ditambahkan.');
    }

    public function edit(Ikan $ikan)
    {
        return view('admin.ikan.edit', compact('ikan'));
    }

    public function update(Request $request, Ikan $ikan)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'nullable|string|max:100',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        // kalau user upload gambar baru → kirim lagi ke Cloudinary
        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');

                $response = Http::asMultipart()->post(
                    'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                    [
                        [
                            'name'     => 'file',
                            'contents' => fopen($file->getRealPath(), 'r'),
                            'filename' => $file->getClientOriginalName(),
                        ],
                        [
                            'name'     => 'upload_preset',
                            'contents' => env('CLOUDINARY_UPLOAD_PRESET'),
                        ],
                    ]
                );

                $result = $response->json();

                if (isset($result['secure_url'])) {
                    $data['gambar'] = $result['secure_url'];
                } else {
                    return back()
                        ->withErrors([
                            'gambar' => 'Cloudinary upload error: ' . ($result['error']['message'] ?? 'Unknown error'),
                        ])
                        ->withInput();
                }
            } catch (\Exception $e) {
                return back()
                    ->withErrors([
                        'gambar' => 'Cloudinary error: ' . $e->getMessage(),
                    ])
                    ->withInput();
            }
        }

        $ikan->update($data);

        return redirect()->route('admin.ikan.index')
            ->with('success', 'Data ikan berhasil diperbarui.');
    }

    public function destroy(Ikan $ikan)
    {
        // Di sini kita hanya hapus data di database.
        // Kalau mau sekalian hapus gambar di Cloudinary,
        // perlu simpan `public_id` juga, bukan cuma `secure_url`.
        $ikan->delete();

        return redirect()->route('admin.ikan.index')
            ->with('success', 'Data ikan berhasil dihapus.');
    }
}
