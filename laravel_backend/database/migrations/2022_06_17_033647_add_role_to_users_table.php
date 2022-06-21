<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_code')->after('email_verified_at');
            $table->foreign('role_code')->references('code')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'role_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['role_code']);
                $table->dropColumn('role_code');
            });
        }
    }
}
