<?php
namespace App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model{
    
    protected $table = 'tbl_company_info';
    
    protected $primaryKey = 'company_info_id';
    
    protected $fillable = array('company_id','owner_first_name','owner_middle_name','owner_last_name','address','email','contact_number');
    
    protected $guarded = array('company_info_id');
    
    public $timestamps = false;
    
//JOINS
    //PERMISSION TABLE
    public function company()
    {
        return $this->belongsTo('App\Models\Company\Company', 'company_id', 'company_id');
    }
}
