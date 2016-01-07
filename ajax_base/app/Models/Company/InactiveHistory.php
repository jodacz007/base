<?php
namespace App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class InactiveHistory extends Model{
    
    protected $table = 'tbl_company_inactive_history';
    
    protected $primaryKey = 'company_inactive_id';
    
    protected $fillable = array('reason');
    
    protected $guarded = array('company_inactive_id');
    
    public $timestamps = false;
    
//JOINS
    //PERMISSION TABLE
    public function company()
    {
        return $this->belongsTo('App\Models\Company\Company', 'company_id', 'company_id');
    }
}
