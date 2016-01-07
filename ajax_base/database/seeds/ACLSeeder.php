<?php
use App\Models\ACL\Action as Action;
use App\Models\ACL\Module as Module;
use App\Models\ACL\Page as Page;
use App\Models\ACL\Permission as Permission;

class ACLSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        DB::table('tbl_permission')->delete();
        DB::table('tbl_page')->delete();
        DB::table('tbl_module')->delete();

        $default_group_id = 1;
        
        $data = array(
            array(
                'module_id' => 1,
                'module_name' => 'Settings',
                'icon'  => 'fa fa-cog',
                'pages' => array(
                    //USER
                    array(
                        'page_id' => 1,
                        'page_name' => 'Users',
                        'page_url' => 'setting/users',
                        'icon'  => 'icon-user',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //GROUP
                    array(
                        'page_id' => 2,
                        'page_name' => 'Group',
                        'page_url' => 'setting/group',
                        'icon'  => 'icon-users',
                        // 'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //Company
                    array(
                        'page_id' => 3,
                        'page_name' => 'Company',
                        'page_url' => 'setting/company',
                        'icon'  => 'fa fa-bank',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    )
                ),
            ),
            array(
                'module_id' => 2,
                'module_name' => 'Brands',
                'icon'  => 'fa fa-cog',
                'pages' => array(
                    //Brand
                    array(
                        'page_id' => 4,
                        'page_name' => 'Brand',
                        'page_url' => 'brands/brand',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //Brand Field
                    array(
                        'page_id' => 5,
                        'page_name' => 'Brand Field',
                        'page_url' => 'brands/field',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //Brand Sites
                    array(
                        'page_id' => 6,
                        'page_name' => 'Brand Sites',
                        'page_url' => 'brands/sites',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                ),
            ),
            array(
                'module_id' => 3,
                'module_name' => 'Leads',
                'icon'  => 'fa fa-cog',
                'pages' => array(
                    //Sold Lead
                    array(
                        'page_id' => 7,
                        'page_name' => 'Sold Lead',
                        'page_url' => 'leads/sold',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //Valid Lead
                    array(
                        'page_id' => 8,
                        'page_name' => 'Valid Lead',
                        'page_url' => 'leads/valid',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                    //Invalid Lead
                    array(
                        'page_id' => 9,
                        'page_name' => 'Invalid Lead',
                        'page_url' => 'leads/invalid',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                ),
            ),
            array(
                'module_id' => 4,
                'module_name' => 'Buyer',
                'icon'  => 'fa fa-cog',
                'pages' => array(
                    //Buyer
                    array(
                        'page_id' => 10,
                        'page_name' => 'Buyers',
                        'page_url' => 'buyer/buyer',
                        'icon'  => 'fa fa-star',
                        'page_max_action' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
                        'permission' => array(
                            'group_id' => $default_group_id,
                        )
                    ),
                ),
            ),
        );
        
        $this->saveModules($data);
    }
    
    private function saveModules($data){
        foreach($data as $module){
            Module::create(
                array(
                    'module_id' => $module['module_id'],
                    'module_name' => $module['module_name'],
                    'icon' => $module['icon'],
                )
            );
            
            foreach($module['pages'] as $page){
                Page::create(
                    array(
                        'page_id' => $page['page_id'],
                        'module_id' => $module['module_id'],
                        'page_name' => $page['page_name'],
                        'page_url' => $page['page_url'],
                        'page_max_action' => $page['page_max_action'],
                        'icon' => $page['icon'],
                    )
                );
                $permission = $page['permission'];
                Permission::create(
                    array(
                        'group_id' => $permission['group_id'],
                        'module_id' => $module['module_id'],
                        'page_id' => $page['page_id'],
                        'action_value' => $page['page_max_action'],
                    )
                );
            }
        }
    }
}