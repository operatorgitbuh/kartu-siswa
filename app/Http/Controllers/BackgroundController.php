<?php

namespace App\Http\Controllers;

use App\Models\Background;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackgroundController extends Controller
{
    public function index()
    {
        $backgrounds = Background::latest()->get();
        return view('pages.backgrounds.index', compact('backgrounds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'is_active' => $request->is_active,
        ];

        if ($request->hasFile('background_front')) {
            $data['background_front'] = $request->file('background_front')->store('backgrounds', 'public');
        }

        if ($request->hasFile('background_back')) {
            $data['background_back'] = $request->file('background_back')->store('backgrounds', 'public');
        }

        Background::create($data);

        return back()->with('success', 'Template berhasil dibuat!');
    }

    public function update(Request $request, Background $background)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean', // Tambahkan validasi status
            'background_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // KUNCI: Masukkan is_active ke dalam array data
        $data = $request->only(['name', 'is_active']);

        // Handle Background Front
        if ($request->hasFile('background_front')) {
            if ($background->background_front) {
                Storage::disk('public')->delete($background->background_front);
            }
            $data['background_front'] = $request->file('background_front')->store('backgrounds', 'public');
        }

        // Handle Background Back
        if ($request->hasFile('background_back')) {
            if ($background->background_back) {
                Storage::disk('public')->delete($background->background_back);
            }
            $data['background_back'] = $request->file('background_back')->store('backgrounds', 'public');
        }

        $background->update($data);

        return back()->with('success', 'Background berhasil diperbarui!');
    }

    public function destroy(Background $background)
    {
        // Hapus file dari storage agar folder public tidak penuh sampah
        if ($background->background_front) {
            Storage::disk('public')->delete($background->background_front);
        }

        if ($background->background_back) {
            Storage::disk('public')->delete($background->background_back);
        }

        // Hapus record dari database
        $background->delete();

        return back()->with('success', 'Template berhasil dihapus!');
    }
}
