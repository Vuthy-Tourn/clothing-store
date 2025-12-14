<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'discount_type')) {
                $table->enum('discount_type', ['percentage', 'fixed'])->nullable()->after('is_new');
            }
            if (!Schema::hasColumn('products', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            }
            if (!Schema::hasColumn('products', 'discount_start')) {
                $table->datetime('discount_start')->nullable()->after('discount_value');
            }
            if (!Schema::hasColumn('products', 'discount_end')) {
                $table->datetime('discount_end')->nullable()->after('discount_start');
            }
            if (!Schema::hasColumn('products', 'has_discount')) {
                $table->boolean('has_discount')->default(false)->after('discount_end');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'discount_start', 'discount_end', 'has_discount']);
        });
    }
};