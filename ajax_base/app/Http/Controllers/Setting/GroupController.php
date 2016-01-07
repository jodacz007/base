<?php
namespace App\Http\Controllers\Setting;
use App\Models\ACL\Permission as Permission;
use App\Models\ACL\User as User;
use App\Models\ACL\Action as Action;
use App\Models\ACL\Group as Group;
use App\Models\Company\Company as Company;
use Request;
use Input;
use Auth;
use Controller;

class GroupController extends Controller {
    protected $page_module = 1;
	protected $page_id = 2;

	public function __construct()
	{
	    $this->middleware('auth');
	}
        
	public function index(){
		$this->checkModule();
		$action_value = Permission::getPageAction($this->page_module,$this->page_id);
		return view('content.setting.group.group',array(
			'action_value' => $action_value,
		));
	}
	
	public function view($id){
		$this->checkPageAction($this->page_id,Action::$View);
		if (!Group::find($id)){
			$this->diplayError(404);
		}
		return view('content.setting.group.modal.group_info',array('group_info' => Group::find($id)));
	}
	
	public function add(){
		$this->checkPageAction($this->page_id,Action::$Add);
		if (Request::ajax())
		{
			if (Input::has('actionType')){
				switch(Input::get('actionType')){
					case 'checkGroupName':
						if (Input::has('group_name') && Input::has('company_id')){
							$group_name = Input::get('group_name');
							$company_id = Input::get('company_id');
							$group = Group::where(
								array(
									'group_name' => $group_name,
									'company_id' => $company_id,
								)
							)->get();
							if (count($group) > 0){
								return 'false';
							}
						}
						return 'true';
						break;
					case 'addGroup':
						if (Request::isMethod('post') && Input::has('company_id') && Input::has('group_name') && Input::has('data')){
							$data = json_decode(Input::get('data'),TRUE);
							$group_name = Input::get('group_name');
							$company_id = Input::get('company_id');

							$group = Group::where(
								array(
									'group_name' => $group_name,
									'company_id' => $company_id,
								)
							)->get();
							if (count($group) > 0 || count($data) == 0){
								return json_encode(array('msg'=>'Unable to add group, Please try again later!'));
							}
							else{
								$group = new Group();
								$group->group_name = $group_name;
								$group->company_id = $company_id;
								if ($group->save()){
									//$group_id = $group->group_id;
									$permissions = array();
									foreach($data as $permission_data){
										$permissions[] = new Permission($permission_data);
									}
									if ($group->permission()->saveMany($permissions)){
										return json_encode(array('status'=>'true','msg'=>'Successfully Added Group!'));
									}
									else{
										$group->delete();
										return json_encode(array('status'=>'false','msg'=>'Unable To Add Group!'));
									}
								}
								return json_encode(array('status'=>'false','msg'=>'Unable To Add Group!'));

							}
						}
						return json_encode(array('status'=>'false','msg'=>'Invalid Request!'));
						break;
				}
			}
		}
		$user = User::find(Auth::id());
		$group_id = $user->group_id;
		$company_id = $user->group->company_id;
		return view('content.setting.group.form.group_form',array(
			'group_id' => $group_id,
			'company_id' => $company_id,
		));
	}
	
	public function edit($id){
		$this->checkPageAction($this->page_id,Action::$Edit);
		if (!Group::find($id)){
			$this->diplayError(404);
		}
		if (Request::ajax() && Input::has('actionType'))
		{
			switch(Input::get('actionType')){
				case 'updateGroup':
					if (Request::isMethod('post') && Input::has('data')){
						$data = json_decode(Input::get('data'),TRUE);
						if (!Group::find($id)){
							return json_encode(array('msg'=>'Unable to update, Please try again later!'));
						}
						else{
							$group = Group::find($id);
							$prevData = $group->permission;
							$group->permission()->delete();
							$permissions = array();
							foreach($data as $permission_data){
								$permissions[] = new Permission($permission_data);
							}
							if ($group->permission()->saveMany($permissions)){
								return json_encode(array('status'=>'true','msg'=>'Successfully Updated Group!'));
							}
							else{
								$group->permission()->saveMany($prevData);
								return json_encode(array('status'=>'false','msg'=>'Unable To Update Group!'));
							}
							return json_encode(array('status'=>'false','msg'=>'Unable To Update Group!'));
							
						}
					}
					return json_encode(array('status'=>'false','msg'=>'Unable To Update Group!'));
					break;
			}
		}

		$user = User::find(Auth::id());
		$group_id = $user->group_id;
		$company_id = $user->group->company_id;
		return view('content.setting.group.form.group_form',array(
			'group_id' => $group_id,
			'company_id' => $company_id,
			'group_data' => Group::find($id)
		));
	}
	
	public function delete($id){
		$this->checkPageAction($this->page_id,Action::$Delete);
		if (!Group::find($id)){
			$this->diplayError(404);
		}
		if (Request::ajax())
		{
			$group = Group::find($id);
			$response['status'] = false;
			if (count(User::where('remove_status','<>','Active')->where('group_id',$id)->get())>0){
				return json_encode($response);
			}
			if ($group->permission()->delete() && $group->delete()){
				$response['status'] = true;
			}
			return json_encode($response);
		}
	}

	public function data(){
		$this->checkPageAction($this->page_id,Action::$View);
		if (Request::ajax())
		{
			if (Input::has('draw') && Input::get('draw')){
				$model_name = 'App\Models\ACL\Group';
				$data = array(
					'tbl_company.company_name',
					'tbl_group.group_name',
					'tbl_group.group_name',
					'tbl_group.group_name',
					'tbl_group.group_id',
				);
				$joins = array(
					'tbl_company,tbl_company.company_id,=,tbl_group.company_id,inner',
				);

				$user = User::find(Auth::id());
				$company_id = $user->group->company_id;

				$where_raw = 'tbl_group.group_id <> 1 ';
				$where_array = array();

				if ($company_id != 1){
					$where_raw .= 'AND tbl_group.group_name <> "Admin"';
					$where_array['company_id'] = $company_id;
				}

				$start = Input::get('start');
				$length = Input::get('length');
				$search = Input::get('search');
				$order = Input::get('order');
				$response = $this->filterData($model_name,$data,$start,$length,$order,$search,$joins,$where_array,$where_raw);
				foreach ($response['data'] as &$data) {
					$data['modules'] = $this->getModules($data->group_id);
					$data['pages'] = $this->getPages($data->group_id);
				}
				return json_encode($response);
			}
		}
	}

	private function getModules($group_id){
		$modules_list = '';
		$group = Group::find($group_id);
        foreach($group->permission->groupBy('module_id') as $modules){
        	$modules_list .= '<a class="btn default btn-xs purple">'.($modules[0]->module->module_name).'</a>';
        }
        return $modules_list;
	}

	private function getPages($group_id){
		$pages_list = '';
		$group = Group::find($group_id);
        foreach($group->permission->groupBy('module_id') as $modules){
            foreach($modules as $pages){
	            $pages_list .= '<a class="btn default btn-xs blue">'.($pages->page->page_name).'</a>';
            }
        }
        return $pages_list;
	}
	
}
