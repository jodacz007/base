<?php
namespace App\Http\Controllers\API;
use App\Models\ACL\User as User;
use App\Models\Brand\Brand as Brand;
use App\Models\Brand\BrandField as BrandField;
use App\Models\Brand\BrandSite as BrandSite;
use App\Models\Leads\Lead as Lead;
use App\Models\Leads\LeadInfo as LeadInfo;
use Carbon;
use Request;
use Input;
use Hash;
use Auth;
use Controller;

class APIController extends Controller {
	protected $layout = 'main';

	public function __construct(){
	    // $this->middleware('auth');
	}
        
	public function index($key){

		$brand = Brand::where('api_key',$key)->first();

		if (!is_null($brand) && !empty($brand)){

			if(!$brand->status){
				return $this->response();
			}

			//CHECK FIELDs
			$data = Input::all();
			$valid_status = BrandField::checkField($brand->brand_id,$data);

			//Save Lead
			$lead = new Lead();
			$data = json_encode($data);
			$lead->fill(
				array(
					'brand_id' => $brand->brand_id,
					'data'	=> $data,
					'date_entered' => Carbon::now(),
					'valid_status' => $valid_status,
				)
			);
			if ($lead->save()){
				//Save Lead Information
				$ip = $this->getServerData('REMOTE_ADDR');
				$referrer = $this->getServerData('HTTP_REFERER');
				$leadInfo = new LeadInfo(
					array(
						'ip' => $ip,
						'referrer' => $referrer,
					)
				);
				$lead->leadInfo()->save($leadInfo);
			}

			if ($valid_status){
				return $this->response('Successfully save data, thank you!',true);
			}
			else{
				return $this->response('Invalid data format, Please verify fields!');
			}
		}
		return $this->response();
	}

	private function response($msg='Invalid Request!',$status=false){
		return json_encode(
			array(
				'error_msg' => $msg,
				'status' => $status,
			)
		);
	}

	private function getServerData($data){
		$ref = 'Undefined';

		$server_data = Request::server();

		if (isset($server_data[$data])){
			$ref = $server_data[$data];
		}

		return $ref;
	}

	private function get_ip() {
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} 
		else {
			$headers = $_SERVER;
		}
		//Get the forwarded IP if it exists
		if (array_key_exists( 'X-Forwarded-For', $headers ) 
			&& filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
			$the_ip = $headers['X-Forwarded-For'];
		} 
		elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) 
			&& filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) 
		{
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} 
		else {
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}

}
