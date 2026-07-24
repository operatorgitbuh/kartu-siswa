<?php

namespace App\Http\Controllers;

use App\Models\Card;

// use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing-page');
    }
    public function checkStatus($nisn)
    {
        $card = Card::whereHas('student', function ($query) use ($nisn) {
            $query->where('nisn', $nisn);
        })
            ->with('student')
            ->first();

        if ($card) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama'     => $card->student->name, // Pastikan 'name' atau 'nama' sesuai kolom DB
                    'foto'     => $card->foto ? asset('storage/' . $card->foto) : asset('images/default.jpg'),
                    'status'   => $card->status,
                    'exp_date' => \Carbon\Carbon::parse($card->exp_date)->translatedFormat('d F Y'),
                ]
            ]);
        }

        // UBAH 404 MENJADI 200 DI SINI
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 200);
    }
}
