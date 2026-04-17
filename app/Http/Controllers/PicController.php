<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function index(Request $request)
    {
        $query = Pic::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%');
            });
        }

        $pics = $query->orderBy('name')->paginate(25)->withQueryString();
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
}
