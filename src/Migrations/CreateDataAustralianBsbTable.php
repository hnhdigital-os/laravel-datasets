<?php

namespace Bluora\LaravelDatasets\Migrations;

use Bluora\LaravelDatasets\Traits\MigrationsTrait;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDataAustralianBsbTable extends Migration
{
    use MigrationsTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_australian_bsb', function (Blueprint $table) {
            self::standardTableStart($table);
            $table->string('bsb', 7)->default('');
            $table->string('bank', 3)->default('');
            $table->string('branch', 255)->default('');
            $table->string('address', 255)->default('');
            $table->string('suburb', 255)->default('');
            $table->string('state', 3)->default('');
            $table->smallInteger('postcode');
            $table->string('payment_types', 255)->default('');
            self::standardTableEnd($table, 'data_australian_bsb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        self::standardTableDrop('data_australian_bsb');
    }
}
