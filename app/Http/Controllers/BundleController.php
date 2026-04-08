<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::withCount('journals')
            ->orderBy('id', 'desc')
            ->get();
            
        return view('bundles.index', compact('bundles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:operasional,vendor',
        ]);

        // Validasi: Pastikan tidak ada bundle dengan TIPE YANG SAMA yang sedang open
        $openBundle = Bundle::where('status', 'open')->where('type', $request->type)->first();
        if ($openBundle) {
            return back()
                ->withErrors(['Bundle ' . strtoupper($request->type) . ' masih ada yang terbuka (#' . $openBundle->bundle_number . '). Silakan tutup terlebih dahulu.']);
        }

        // Generate nomor bundle (01-OPS, 01-VEN, dst)
        $latestOfType = Bundle::where('type', $request->type)->latest('id')->first();
        $nextNumber = 1;

        if ($latestOfType) {
            // Ambil angka didepan tanda minus
            preg_match('/^(\d+)/', $latestOfType->bundle_number, $matches);
            $nextNumber = intval($matches[1] ?? 0) + 1;
        }

        $suffix = $request->type === 'vendor' ? 'VEN' : 'OPS';
        $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '-' . $suffix;

        Bundle::create([
            'bundle_number' => $formattedNumber,
            'status' => 'open',
            'type' => $request->type,
        ]);

        return back()->with('success', 'Bundle ' . $formattedNumber . ' berhasil dibuka.');
    }

    public function close(Bundle $bundle)
    {
        if ($bundle->status !== 'open') {
            return redirect()->route('bundles.index')
                ->withErrors(['Bundle ini sudah ditutup.']);
        }

        $bundle->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('bundles.index')
            ->with('success', 'Bundle ' . $bundle->bundle_number . ' berhasil ditutup.');
    }

    public function reopen(Bundle $bundle)
    {
        if ($bundle->status === 'open') {
            return redirect()->route('bundles.index')
                ->withErrors(['Bundle ini memang sedang terbuka.']);
        }

        // Pastikan tidak ada bundle DENGAN TIPE YANG SAMA yang sedang open
        $openBundle = Bundle::where('status', 'open')->where('type', $bundle->type)->first();
        if ($openBundle) {
            return redirect()->route('bundles.index')
                ->withErrors(['Tidak bisa membuka ulang, karena Bundle ' . $openBundle->bundle_number . ' (' . strtoupper($bundle->type) . ') masih terbuka.']);
        }

        $bundle->update([
            'status' => 'open',
            'closed_at' => null,
        ]);

        return redirect()->route('bundles.index')
            ->with('success', 'Bundle ' . $bundle->bundle_number . ' berhasil dibuka kembali.');
    }
}
