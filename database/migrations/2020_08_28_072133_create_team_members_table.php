<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->uuid('team_id');
            $table->index('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @param $table
     * @return void
     */
    public function down()
    {
        Schema::table('team_members', function(Blueprint $table)
        {
            $table->dropForeign('team_members_team_id_foreign');
            $table->dropIndex('team_members_team_id_index');
            $table->dropColumn('team_id');
        });
        Schema::dropIfExists('team_members');
    }
}
