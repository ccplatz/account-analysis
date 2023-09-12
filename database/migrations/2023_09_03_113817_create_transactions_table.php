<?php

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('date');
            $table->string('name_other_party');
            $table->string('iban_other_party');
            $table->string('payment_type');
            $table->string('purpose');
            $table->float('value');
            $table->float('balance_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};