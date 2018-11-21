<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Student_ID',8);
            $table->string('Group_ID',2);
            $table->string('TBS_Tutor_ID',8);
            $table->string('LNS_Tutor_ID',8);
            $table->string('SPK1_Tutor_ID',8);
            $table->string('SPK2_Tutor_ID',8);
            $table->string('Listen_Test',6)->nullable()->default('NULL');
            $table->string('Read_Test',6)->nullable()->default('NULL');
            $table->string('Write_Test',6)->nullable()->default('NULL');
            $table->string('Speak_Test')->nullable()->default('NULL');
            $table->string('Listen_Class',6)->nullable()->default('NULL');
            $table->string('Read_Class',6)->nullable()->default('NULL');
            $table->string('Write_Class',6)->nullable()->default('NULL');
            $table->string('Speak_Class',6)->nullable()->default('NULL');
            $table->string('Project_Written',6)->nullable()->default('NULL');
            $table->string('Project_Seminar',6)->nullable()->default('NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}
