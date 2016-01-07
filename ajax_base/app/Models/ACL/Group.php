<?php
namespace App\Models\ACL;
use Illuminate\Database\Eloquent\Model;

class Group extends Model{
    
    protected $table = 'tbl_group';
    
    protected $primaryKey = 'group_id';
    
    protected $fillable = array('group_name','company_id');
    
    protected $guarded = array('group_id');
    
    public $timestamps = false;
    
//JOINS
    //PERMISSION TABLE
    public function permission()
    {
        return $this->hasMany('App\Models\ACL\Permission', 'group_id', 'group_id');
    }
    //USER TABLE
    public function user()
    {
        return $this->hasMany('App\Models\ACL\User', 'group_id', 'group_id');
    }
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company\Company','company_id','company_id');
    }

//Default Values
    public static $Admin = 'Admin';
    public static $Manager = 'Account Manager';

    public static function getGroupList(){
        return array(
            Group::$Admin   => Group::$Admin,
            Group::$Manager => Group::$Manager,
        );
    }
}
