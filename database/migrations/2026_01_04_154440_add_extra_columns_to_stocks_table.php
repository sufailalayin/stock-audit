<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'brand_name')) {
                $table->string('brand_name')->nullable()->after('item_name');
            }

            if (!Schema::hasColumn('stocks', 'size')) {
                $table->string('size')->nullable()->after('brand_name');
            }

            if (!Schema::hasColumn('stocks', 'color')) {
                $table->string('color')->nullable()->after('size');
            }
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'brand_name')) {
                $table->dropColumn('brand_name');
            }

            if (Schema::hasColumn('stocks', 'size')) {
                $table->dropColumn('size');
            }

            if (Schema::hasColumn('stocks', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
