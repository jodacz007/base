<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Admin Routes
Route::group(['middleware'=>'auth','prefix' => '/'], function(){
    Route::get('/', 'DashboardController@index');
//My Account
    Route::group(array('prefix'=> 'myprofile'), function(){
        Route::get('/', 'MyProfile\MyAccountController@index');
        Route::post('update', 'MyProfile\MyAccountController@edit');
        Route::post('upload', 'MyProfile\MyAccountController@uploadPic');
    });
    
    //Setting Routes
    Route::group(array('prefix'=> 'setting'), function(){
        //USERS
        Route::group(array('prefix'=> 'users'), function(){
            Route::get('/', 'Setting\UserController@index');
            Route::get('view/{id}', 'Setting\UserController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Setting\UserController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Setting\UserController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Setting\UserController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Setting\UserController@data')->where('id', '[0-9]+');
        });
        //GROUP
        Route::group(array('prefix'=> 'group'), function(){
            Route::get('/', 'Setting\GroupController@index');
            Route::get('view/{id}', 'Setting\GroupController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Setting\GroupController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Setting\GroupController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Setting\GroupController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Setting\GroupController@data')->where('id', '[0-9]+');
        });
        //Company
        Route::group(array('prefix'=> 'company'), function(){
            Route::get('/', 'Setting\CompanyController@index');
            Route::get('view/{id}', 'Setting\CompanyController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Setting\CompanyController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Setting\CompanyController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Setting\CompanyController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Setting\CompanyController@data')->where('id', '[0-9]+');
        });
    });

    //Brand Routes
    Route::group(array('prefix'=> 'brands'), function(){
        //Brand
        Route::group(array('prefix'=> 'brand'), function(){
            Route::get('/', 'Brand\BrandController@index');
            Route::get('view/{id}', 'Brand\BrandController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Brand\BrandController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Brand\BrandController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Brand\BrandController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Brand\BrandController@data')->where('id', '[0-9]+');
        });
        //Brand Field
        Route::group(array('prefix'=> 'field'), function(){
            Route::get('/', 'Brand\BrandFieldController@index');
            Route::get('view/{id}', 'Brand\BrandFieldController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Brand\BrandFieldController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Brand\BrandFieldController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Brand\BrandFieldController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Brand\BrandFieldController@data')->where('id', '[0-9]+');
        });
        //Brand Sites
        Route::group(array('prefix'=> 'sites'), function(){
            Route::get('/', 'Brand\BrandSitesController@index');
            Route::get('view/{id}', 'Brand\BrandSitesController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Brand\BrandSitesController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Brand\BrandSitesController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Brand\BrandSitesController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Brand\BrandSitesController@data')->where('id', '[0-9]+');
        });
    });

    //Leads Routes
    Route::group(array('prefix'=> 'leads'), function(){
        //Sold Lead
        Route::group(array('prefix'=> 'sold'), function(){
            Route::get('/', 'Lead\SoldLeadController@index');
            Route::get('view/{id}', 'Lead\SoldLeadController@view')->where('id', '[0-9]+');
            Route::get('data', 'Lead\SoldLeadController@data')->where('id', '[0-9]+');
        });
        //Valid Lead
        Route::group(array('prefix'=> 'valid'), function(){
            Route::get('/', 'Lead\ValidLeadController@index');
            Route::get('view/{id}', 'Lead\ValidLeadController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'update/{id}', 'Lead\ValidLeadController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Lead\ValidLeadController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Lead\ValidLeadController@data')->where('id', '[0-9]+');
        });
        //Invalid Lead
        Route::group(array('prefix'=> 'invalid'), function(){
            Route::get('/', 'Lead\InvalidLeadController@index');
            Route::get('view/{id}', 'Lead\InvalidLeadController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'update/{id}', 'Lead\InvalidLeadController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Lead\InvalidLeadController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Lead\InvalidLeadController@data')->where('id', '[0-9]+');
        });
    });

    //Buyer
    Route::group(array('prefix'=> 'buyer'), function(){
        //Valid Lead
        Route::group(array('prefix'=> 'buyer'), function(){
            Route::get('/', 'Buyer\BuyerController@index');
            Route::get('view/{id}', 'Buyer\BuyerController@view')->where('id', '[0-9]+');
            Route::match(array('GET','POST'),'add', 'Buyer\BuyerController@add');
            Route::match(array('GET','POST'),'update/{id}', 'Buyer\BuyerController@edit')->where('id', '[0-9]+');
            Route::post('delete/{id}', 'Buyer\BuyerController@delete')->where('id', '[0-9]+');
            Route::get('data', 'Buyer\BuyerController@data')->where('id', '[0-9]+');
            Route::get('brand/data', 'Buyer\BuyerController@brandData')->where('id', '[0-9]+');
        });
    });
});

Route::group(['prefix' => 'api/{key}'], function(){
    Route::match(array('GET','POST'),'/', 'API\APIController@index');
});

Route::match(array('get', 'post'),'login',function(){
    if (Request::isMethod('post') && Input::has('username') && Input::has('password')){
        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(array('username' => $username, 'password' => $password, 'status' => 'Active', 'remove_status' => 'Inactive'))){
            $user = App\Models\ACL\User::find(Auth::id());
            if ($user->group->company->company_status == 'Inactive'){
                Auth::logout();
            }
            Session::put(Auth::getName().'_type', 'admin');
            return Redirect::to('/');
        }
    }
    else if (Auth::id()){
        Auth::logout();
    }
    return view('login');
});

Route::get('logout',function(){
    if (Auth::id()){
        Session::forget(Auth::getName().'_type');
        $user = App\Models\ACL\User::find(Auth::id());
        $user->last_login = Carbon\Carbon::now();
        $user->save();
        Auth::logout();
    }
    return Redirect::to('/');
});