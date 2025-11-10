<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('numero')->nullable()->unique()->after('id');
        });

        // tabla simple para mantener el contador por año
        Schema::create('factura_counters', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->integer('counter')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('numero');
        });

        Schema::dropIfExists('factura_counters');
    }
};
