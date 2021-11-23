<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanbanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanban', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->text('title');
            $table->enum('status', ['0','1','2','3'])->comment('0 => Not Started , 1 => In Progress, 2 => Unit Testing, 3 => Completed');
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
        Schema::dropIfExists('kanban');
    }
}
