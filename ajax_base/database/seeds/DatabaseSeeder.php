<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	
	public function run(){
		Eloquent::unguard();
		$this->clearTable();
		$this->call('ActionSeeder');
		$this->call('CompanySeeder');
		$this->call('GroupSeeder');
		$this->call('UserSeeder');
		$this->call('ACLSeeder');
	}
	
	private function clearTable(){
		//ACL
		DB::table('tbl_action')->delete();
		DB::table('tbl_permission')->delete();
		DB::table('tbl_page')->delete();
		DB::table('tbl_module')->delete();
		DB::table('tbl_user_info')->delete();
		DB::table('tbl_users')->delete();
		DB::table('tbl_group')->delete();

		//Company
		DB::table('tbl_company_info')->delete();
		DB::table('tbl_company')->delete();
	}

}