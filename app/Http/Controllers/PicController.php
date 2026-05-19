<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function index(Request $request)
    {
        $pics = Pic::orderBy('name')->get();
        return view('pics.index', compact('pics'));
    }

    public function create()
    {
        return view('pics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Pic::create($request->all());

        return redirect()->route('pics.index')->with('success', 'PIC berhasil ditambahkan.');
    }

    public function edit(Pic $pic)
    {
        return view('pics.edit', compact('pic'));
    }

    public function update(Request $request, Pic $pic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $pic->update($request->all());

        return redirect()->route('pics.index')->with('success', 'PIC berhasil diperbarui.');
    }

    public function destroy(Pic $pic)
    {
        $pic->delete();

        return redirect()->route('pics.index')->with('success', 'PIC berhasil dihapus.');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $pic = Pic::create([
            'name' => strtoupper($request->name),
            'description' => 'Ditambahkan otomatis oleh Finance',
        ]);

        return response()->json([
            'success' => true,
            'data' => $pic
        ]);
    }
}
