<?php

use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_rules', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignIdFor(Account::class);
            $table->string('field_name');
            $table->string('pattern', 1000);
            $table->boolean('exact_match')->default(false);
            $table->integer('sequence')->default(0);
            $table->foreignIdFor(Category::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_rules');
    }
};
