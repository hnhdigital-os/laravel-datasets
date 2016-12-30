<?php

namespace Bluora\LaravelDatasets\Migrations;

use Bluora\LaravelDatasets\Traits\MigrationsTrait;
use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLanguageCodesTable extends Migration
{
    use MigrationsTrait;

    protected $table_name = 'data_language_codes';

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
            $table->string('iso3166_1_alpha_2', 2)->default('');
            $table->string('iso3166_1_alpha_3', 7)->default('');
            $table->string('iso3166_1_alpha_3t', 7)->default('');
            $table->string('english', 255)->default('');
            $table->string('french', 255)->default('');
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
        Schema::drop($this->table_name);
    }
}
