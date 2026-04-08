<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('name')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:accounts,code',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')->with('success', 'Kode Akun berhasil ditambahkan.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:accounts,code,'.$account->id,
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.index')->with('success', 'Kode Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Kode Akun berhasil dihapus.');
    }
}
