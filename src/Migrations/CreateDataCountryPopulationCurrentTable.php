<?php

namespace Bluora\LaravelDatasets\Migrations;

use Bluora\LaravelDatasets\Traits\MigrationsTrait;
use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataCountryPopulationCurrentTable extends Migration
{
    use MigrationsTrait;

    protected $table_name = 'data_country_population_current';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid', 16);
            $table->string('name', 255)->default('');
            $table->string('code', 3)->default('');
            $table->integer('year');
            $table->bigInteger('population');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        self::updateUuid($this->table_name, 'uuid');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        self::dropUuidTrigger($this->table_name);
        self::standardTableDrop($this->table_name);
    }
}
