<?php
namespace App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Company extends Model{
    
    protected $table = 'tbl_company';
    
    protected $primaryKey = 'company_id';
    
    protected $fillable = array('company_name');
    
    protected $guarded = array('company_id');
    
    public $timestamps = false;
    
//JOINS
    //Group TABLE
    public function group()
    {
        return $this->hasMany('App\Models\ACL\Group', 'company_id', 'company_id');
    }
    //Brand TABLE
    public function brand()
    {
        return $this->hasMany('App\Models\Brand\Brand', 'company_id', 'company_id');
    }
    //Company Info TABLE
    public function companyInfo()
    {
        return $this->hasOne('App\Models\Company\CompanyInfo', 'company_id', 'company_id');
    }
    //Company Inactive History TABLE
    public function inactiveHistory()
    {
        return $this->hasMany('App\Models\Company\InactiveHistory', 'company_id', 'company_id');
    }
    //Buyer Table
    public function buyer()
    {
        return $this->hasMany('App\Models\Buyer\Buyer', 'company_id', 'company_id');
    }
}
