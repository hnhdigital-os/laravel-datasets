<?php

namespace Bluora\LaravelDatasets\Migrations;

use Bluora\LaravelDatasets\Traits\MigrationsTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAustralianBsbTable extends Migration
{
    use MigrationsTrait;

    protected $table_name = 'data_australian_bsb';

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
            $table->string('bsb', 7)->default('');
            $table->string('bank', 3)->default('');
            $table->string('branch', 255)->default('');
            $table->string('address', 255)->default('');
            $table->string('suburb', 255)->default('');
            $table->string('state', 3)->default('');
            $table->smallInteger('postcode');
            $table->string('payment_types', 255)->default('');
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
