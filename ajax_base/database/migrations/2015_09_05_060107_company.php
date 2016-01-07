<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Company extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            //Company Table
            Schema::create('tbl_company', function($t) {
                $t->increments('company_id');
                $t->string('company_name', 100);
                $t->enum('company_status',array('Inactive','Active'))->default('Active');
            });

            //Company Information Table
            Schema::create('tbl_company_info', function($t) {
                $t->increments('company_info_id');
                $t->integer('company_id')->unsigned();
                $t->foreign('company_id')->references('company_id')->on('tbl_company');
                $t->string('owner_first_name', 45);
                $t->string('owner_middle_name', 45);
                $t->string('owner_last_name', 45);
                $t->string('address', 100);
                $t->string('email', 100);
                $t->string('contact_number', 45);
            });

            //Company Inactive History Table
            Schema::create('tbl_company_inactive_history', function($t) {
                $t->increments('company_inactive_id');
                $t->integer('company_id')->unsigned();
                $t->foreign('company_id')->references('company_id')->on('tbl_company');
                $t->text('reason');
                $t->datetime('datetime');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('tbl_company_inactive_history');
            Schema::drop('tbl_company_info');
            Schema::drop('tbl_company');
	}

}
