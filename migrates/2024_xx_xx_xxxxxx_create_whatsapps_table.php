<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('group_id');
            $table->timestamps();
        });

        Schema::create('taxista_whatsapp', function (Blueprint $table) {
            $table->foreignId('taxista_id')->references('id')->on('taxistas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('whatsapp_id')->references('id')->on('whatsapps')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('taxistas', function (Blueprint $table) {
            if (!Schema::hasColumn('taxistas', 'permiso')) {
                $table->boolean('permiso')->default(false)->after('descuento');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapps');
        Schema::dropIfExists('taxista_whatsapp');
        Schema::table('taxistas', function (Blueprint $table) {
            $table->dropColumn('permiso');
        });
    }
};
