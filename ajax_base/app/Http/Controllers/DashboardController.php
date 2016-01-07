<?php
namespace App\Http\Controllers;
use Request;

class DashboardController extends Controller {
	public function __construct()
	{
	    $this->middleware('auth');
	}
	
	public function index(){
		if (Request::ajax()){
			return view('content.dashboard');
		}
		return view('main2');
	}
}
