<?php
namespace App\Http\Controllers\Setting;
use App\Models\ACL\User as User;
use App\Models\ACL\Group as Group;
use App\Models\ACL\Permission as Permission;
use App\Models\Brand\Brand as Brand;
use Action;
use Request;
use Input;
use Hash;
use Auth;
use Controller;

class UserController extends Controller {
	protected $layout = 'main';
        
	protected $page_module = 1;
	protected $page_id = 1;

	public function __construct(){
	    $this->middleware('auth');
	}
        
	public function index(){
		$this->checkModule();
		$action_value = Permission::getPageAction($this->page_module,$this->page_id);
		return view('content.setting.users.users',array(
			'action_value'=> $action_value,
		));
	}
	
	public function view($id){
		$this->checkPageAction($this->page_id,Action::$View);
		if (!User::find($id)){
			$this->diplayError(404);
		}
		return view('content.setting.users.modal.user_profile',array('user_info' => User::find($id)));
	}
	
	public function add(){
		$this->checkPageAction($this->page_id,Action::$Add);
		if (Request::ajax()){
			if (Input::has('actionType')){
				switch(Input::get('actionType')){
					case 'groupList':
						$group_list = array();
						if (Input::has('company_id')){
							$user = User::find(Auth::id());
							if ($user->group->group_name != 'Admin'){
								$groups[] = $user->group;
							}
							else{
								$company_id = Input::get('company_id');
								$groups = Group::where('company_id',$company_id)->get();
							}

							foreach ($groups as $group) {
								$group_list[] = array(
									'id' => $group->group_id,
									'text' => $group->group_name,
								);
							}
						}
						return json_encode($group_list);
						break;
					case 'checkUsername':
						if (Input::has('username')){
							$user = User::where(array('username' => Input::get('username')))->where('remove_status','<>','Active')->get();
							if (count($user) > 0){
								return 'false';
							}
						}
						return 'true';
						break;
					case 'addUser':
						if (Request::isMethod('post') && Input::has('user') && Input::has('userinfo')){
							$user_detail = json_decode(Input::get('user'),TRUE);
							$userInfo = json_decode(Input::get('userinfo'),TRUE);
							$user = User::where(array('username' => $user_detail['username']))->where('remove_status','<>','Active')->get();
							if (count($user) > 0){
								return json_encode(array('msg'=>'Unable to add user, Please try again later!'));
							}
							else{
								$user = new User;
								$user->fill($user_detail);
								$user->password = Hash::make(User::$DefaultPassword);
								if ($user->save()){
									$userInfo['user_id'] = $user->user_id;
									if ($user->userinfo()->insert($userInfo)){
										return json_encode(array('status'=>'true','msg'=>'Successfully Added User!'));
									}
									else{
										$user->delete();
										return json_encode(array('status'=>'false','msg'=>'Unable To Add User!'));
									}
								}
								return json_encode(array('status'=>'false','msg'=>'Unable To Add User!'));
								
							}
						}
						return json_encode(array('status'=>'false','msg'=>'Invalid Request!'));
						break;
				}
			}
		}
		$user = User::find(Auth::id());
		$company_id = $user->group->company_id;
		return view('content.setting.users.form.user_form',array(
			'company_id' => $company_id,
		));
	}
	
	public function edit($id){
		$this->checkPageAction($this->page_id,Action::$Edit);
		if (!User::find($id) || User::find($id)->remove_status == 'Active'){
			$this->diplayError(404);
		}
		if (Request::ajax() && Input::has('actionType'))
		{
			$responce = array();
			switch(Input::get('actionType')){
				case 'groupList':
					$group_list = array();
					if (Input::has('company_id')){
						$user = User::find(Auth::id());
						if ($user->group->group_name != 'Admin'){
							$groups[] = $user->group;
						}
						else{
							$company_id = Input::get('company_id');
							$groups = Group::where('company_id',$company_id)->get();
						}

						foreach ($groups as $group) {
							$group_list[] = array(
								'id' => $group->group_id,
								'text' => $group->group_name,
							);
						}
					}
					return json_encode($group_list);
					break;
				case 'status':
					$responce['status'] = false;
					if (User::updateStatus($id)){
						$responce['status'] = true;
					}
					return json_encode($responce);
					break;
				case 'resetPwd':
					$responce['msg'] = 'Failed in reseting password!';
					if (User::resetPassword($id)){
						$responce['msg'] = 'Successfully reset password!';
					}
					return json_encode($responce);
					break;
				case 'updateUser':
					if (Request::isMethod('post') && Input::has('user') && Input::has('userinfo')){
						if (!User::find($id)){
							return json_encode(array('msg'=>'Unable to find user, Please try again later!'));
						}
						else{
							$user_detail = json_decode(Input::get('user'),TRUE);
							$userInfo = json_decode(Input::get('userinfo'),TRUE);
							
							$user = User::find($id);

							if (!$this->checkUserValidation($id)){
								return json_encode(array('status'=>'false','msg'=>'Unable to update user due to some restrictions!'));
							}

							$prevData = $user->toArray();
							$user->fill($user_detail);
							if ($user->save()){
								$user->userinfo->fill($userInfo);
								if ($user->userinfo->save()){
									return json_encode(array('status'=>'true','msg'=>'Successfully Updated User!'));
								}
								else{
									$user->fill($prevData);
									$user->save();
									return json_encode(array('status'=>'false','msg'=>'Unable To Update User!'));
								}
							}
							return json_encode(array('status'=>'false','msg'=>'Unable To Update User!'));
							
						}
					}
					break;
			}
		}
		$user = User::find(Auth::id());
		$company_id = $user->group->company_id;
		return view('content.setting.users.form.user_form',array(
			'company_id' => $company_id,
			'user_data' => User::find($id),
		));
	}
	
	public function delete($id){
		$this->checkPageAction($this->page_id,Action::$Delete);
		if (!User::find($id) || User::find($id)->remove_status == 'Active'){
			$this->diplayError(404);
		}
		if ($this->checkUserValidation($id) && Request::ajax())
		{
			$user = User::find($id);
			$response['status'] = false;
			$user->remove_status = 'Active';
			if ($user->save()){
				$response['status'] = true;
			}
			return json_encode($response);
		}
		return json_encode(array('status'=>false));
	}

	public function data(){
		$this->checkPageAction($this->page_id,Action::$View);
		if (Request::ajax())
		{
			if (Input::has('draw') && Input::get('draw')){
				$model_name = 'App\Models\ACL\User';
				$data = array(
					'tbl_user_info.first_name',
					'tbl_user_info.middle_name',
					'tbl_user_info.last_name',
					'tbl_company.company_name',
					'tbl_group.group_name',
					'tbl_users.status',
					'tbl_users.user_id',
				);
				$joins = array(
					'tbl_user_info,tbl_user_info.user_id,=,tbl_users.user_id,inner',
					'tbl_group,tbl_group.group_id,=,tbl_users.group_id,inner',
					'tbl_company,tbl_company.company_id,=,tbl_group.company_id,inner',
				);

				$user = User::find(Auth::id());
				$group_id = $user->group_id;
				$company_id = $user->group->company_id;
				$where_array = array(
					'tbl_users.remove_status' => 'Inactive'
				);
				$where_raw = 'tbl_users.user_id <> '.Auth::id();
				if ($company_id == 1){
					if ($user->group->group_name != 'Admin')
						$where_array['tbl_group.group_id'] = $group_id;
				}
				else{
					$where_array['tbl_company.company_id'] = $company_id;
					if ($user->group->group_name != 'Admin'){
						$where_array['tbl_group.group_id'] = $group_id;
					}
				}
				$start = Input::get('start');
				$length = Input::get('length');
				$search = Input::get('search');
				$order = Input::get('order');
				$response = $this->filterData($model_name,$data,$start,$length,$order,$search,$joins,$where_array,$where_raw);
				return json_encode($response);
			}
		}
	}

	private	function checkUserValidation($user_id){
		$user = User::find($user_id);
		if (!is_null($user->brand) && count($user->brand->toArray()) != 0){
			return false;
		}
		return true;
	}
	
}
