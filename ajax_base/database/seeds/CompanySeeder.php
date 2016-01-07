<?php
use App\Models\Company\Company as Company;
use App\Models\Company\CompanyInfo as CompanyInfo;
class CompanySeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $this->companyList();
    }
    
    private function companyList(){
        $company_id = 1;
        Company::create(
            array(
                'company_id' => $company_id,
                'company_name' => 'Fifth Agency',
            )
        );

        CompanyInfo::create(
            array(
                'company_id' => $company_id,
                'owner_first_name' => 'Sample',
                'owner_middle_name' => 'Sample',
                'owner_last_name' => 'Sample',
                'address' => 'Sample',
                'email' => 'sample@gmail.com',
                'contact_number' => 'Sample',
            )
        );
    }

}