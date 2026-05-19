<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Account;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Account::firstOrCreate(
            ['code' => '2-100'],
            ['name' => 'Hutang PPh 23']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Account::where('code', '2-100')->delete();
    }
};
