<?php
namespace App\Http\Controllers\Setting;
use App\Models\ACL\User as User;
use App\Models\ACL\Permission as Permission;
use App\Models\ACL\Action as Action;
use App\Models\ACL\Group as Group;
use App\Models\Company\Company as Company;
use Carbon;
use Request;
use Input;
use Hash;
use Auth;
use Controller;

class CompanyController extends Controller {
	protected $layout = 'main';
        
	protected $page_module = 1;
	protected $page_id = 3;

	public function __construct(){
	    $this->middleware('auth');
	}
        
	public function index(){
		$this->checkModule();
		$action_value = Permission::getPageAction($this->page_module,$this->page_id);
		$group_id = User::find(Auth::id())->group_id;
		if ($group_id != 1)
			$this->diplayError(404);
		return view('content.setting.company.company',array('action_value'=>$action_value));
	}
	
	public function view($id){
		$this->checkPageAction($this->page_id,Action::$View);
		if (!Company::find($id)){
			$this->diplayError(404);
		}
		return view('content.setting.company.modal.company_info',array('company_info' => Company::find($id)));
	}
	
	public function add(){
		$this->checkPageAction($this->page_id,Action::$Add);
		if (Request::ajax()){
			if (Input::has('actionType')){
				switch(Input::get('actionType')){
					case 'checkCompany':
						if (Input::has('company_name')){
							$name = Input::get('company_name');
							$data = Company::where(array('company_name' => $name))->get();
							if (count($data) > 0){
								return 'false';
							}
						}
						return 'true';
						break;
					case 'addCompany':
						if (Request::isMethod('post') && Input::has('company') && Input::has('companyInfo')){
							$company_detail = json_decode(Input::get('company'),TRUE);
							$companyInfo = json_decode(Input::get('companyInfo'),TRUE);
							$company = Company::where(array('company_name' => $company_detail['company_name']))->get();
							if (count($company) > 0){
								return json_encode(array('msg'=>'Company already exists!'));
							}
							else{
								$company = new Company;
								$company->fill($company_detail);
								if ($company->save()){
									$companyInfo['company_id'] = $company->company_id;
									if ($company->companyInfo()->insert($companyInfo)){
										$this->generateGroups($company->company_id);
										return json_encode(array('status'=>'true','msg'=>'Successfully Added Company!'));
									}
									else{
										$company->delete();
										return json_encode(array('status'=>'false','msg'=>'Unable To Add Company!'));
									}
								}
								return json_encode(array('status'=>'false','msg'=>'Unable To Add Company, Please try again later!'));
								
							}
						}
						return json_encode(array('status'=>'false','msg'=>'Invalid Request!'));
						break;
				}
			}
		}
		return view('content.setting.company.form.company_form');
	}
	
	public function edit($id){
		$this->checkPageAction($this->page_id,Action::$Edit);
		if (!Company::find($id)){
			$this->diplayError(404);
		}
		if (Request::ajax() && Input::has('actionType'))
		{
			$responce = array();
			switch(Input::get('actionType')){
				case 'checkCompany':
					if (Input::has('company_name')){
						$name = Input::get('company_name');
						$data = Company::where(array('company_name' => $name))->where('company_id','<>',$id)->get();
						if (count($data) > 0){
							return 'false';
						}
					}
					return 'true';
					break;
				case 'updateCompany':
					if (Request::isMethod('post') && Input::has('company') && Input::has('companyInfo')){
						if (!Company::find($id)){
							return json_encode(array('msg'=>'Unable to find company info, Please try again later!'));
						}
						else{
							$company_detail = json_decode(Input::get('company'),TRUE);
							$company_info = json_decode(Input::get('companyInfo'),TRUE);
							
							$company = Company::find($id);
							$prevData = $company->toArray();
							$company->fill($company_detail);
							if ($company->save()){
								$company->companyInfo->fill($company_info);
								if ($company->companyInfo->save()){
									return json_encode(array('status'=>'true','msg'=>'Successfully Updated Company!'));
								}
								else{
									$company->fill($prevData);
									$company->save();
									return json_encode(array('status'=>'false','msg'=>'Unable To Update Company!'));
								}
							}
							return json_encode(array('status'=>'false','msg'=>'Unable To Update Company!'));
							
						}
					}
					break;
			}
		}
		return view('content.setting.company.form.company_form',array('company_info' => Company::find($id)));
	}
	
	public function delete($id){
		$this->checkPageAction($this->page_id,Action::$Delete);
		$response = array(
			'status' => false,
			'msg' => 'Unable to find company information!'
		);
		if (Request::ajax() && Company::find($id) && Input::has('reason'))
		{
			$reason = Input::get('reason');
			$company = Company::find($id);
			$company->company_status = ($company->company_status=='Active')?'Inactive':'Active';
			if ($company->save()){
				if ($company->company_status == 'Inactive'){
					$inactiveHistoryInfo = array(
						'company_id' => $id,
						'reason' => $reason,
						'datetime' => Carbon::now(),
					);
					$company->inactiveHistory()->insert($inactiveHistoryInfo);
				}
				$response['status'] = true;
			}
			return json_encode($response);
		}
		return json_encode($response);
	}

	public function data(){
		$this->checkPageAction($this->page_id,Action::$View);
		if (Request::ajax())
		{
			if (Input::has('draw') && Input::get('draw')){
				$model_name = 'App\Models\Company\Company';
				$data = array(
					'company_name',
					'tbl_company_info.owner_first_name',
					'tbl_company_info.owner_middle_name',
					'tbl_company_info.owner_last_name',
					// 'tbl_company_info.address',
					'tbl_company_info.email',
					'tbl_company_info.contact_number',
					'company_status',
					'tbl_company.company_id',
				);
				$joins = array(
					'tbl_company_info,tbl_company_info.company_id,=,tbl_company.company_id,inner',
				);
				$where_raw = 'tbl_company.company_id <> 1';
				$start = Input::get('start');
				$length = Input::get('length');
				$search = Input::get('search');
				$order = Input::get('order');
				$response = $this->filterData($model_name,$data,$start,$length,$order,$search,$joins,array(),$where_raw);
				return json_encode($response);
			}
		}
	}

	public function generateGroups($company_id){
		$group_list = array(
			'0' => 'Admin',
			// '1' => 'Account Manager',
			// '2' => 'Advertiser',
			// '3' => 'Customer Service',
		);
		$permission_list = array(
			'0' => array(
				'1' => array(
					array(
						'page_id' => 1,
						'action_value' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
					),
					array(
						'page_id' => 2,
						'action_value' => Action::$View | Action::$Add | Action::$Edit,
					),
				),
				'2' => array(
					array(
						'page_id' => 4,
						'action_value' => Action::$View | Action::$Add | Action::$Edit | Action::$Delete,
					),
				),
			),
			// '1' => array(
				
			// ),
			// '2' => array(
				
			// ),
			// '3' => array(
				
			// ),
		);
		foreach ($group_list as $key => $group) {
			$new_group = new Group;
			$new_group->fill(
	            array(
	                'group_name' => $group,
	                'company_id' => $company_id,
	            )
			);
			if ($new_group->save()){
				foreach ($permission_list[$key] as $module_key => $pages) {
					foreach ($pages as $page) {
						$new_permission = new Permission;
						$new_permission->group_id = $new_group->group_id;
						$new_permission->module_id = $module_key;
						$new_permission->page_id = $page['page_id'];
						$new_permission->action_value = $page['action_value'];
						$new_permission->save();
					}
				}
			}
		}
	}
	
}
