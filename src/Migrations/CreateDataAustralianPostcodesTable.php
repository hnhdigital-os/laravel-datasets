<?php

namespace Bluora\LaravelDatasets\Migrations;

use Bluora\LaravelDatasets\Traits\MigrationsTrait;
use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAustralianPostcodesTable extends Migration
{
    use MigrationsTrait;

    protected $table_name = 'data_australian_postcodes';

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
            $table->integer('postcode');
            $table->string('suburb')->default('');
            $table->string('state')->default('');
            $table->string('dc')->default('');
            $table->string('type')->default('');
            $table->double('latitude', 8, 6);
            $table->double('longitude', 9, 6);
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
