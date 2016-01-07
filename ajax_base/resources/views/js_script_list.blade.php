<!-- My Account -->
<script src="{{URL::to('/')}}/assets/page/scripts/myaccount/myaccount.js"></script>

@foreach(App\Models\ACL\Permission::where('group_id',App\Models\ACL\User::find(Auth::id())->group_id)->get() as $permission)
	<script src="{{URL::to('/')}}/assets/page/scripts/{{$permission->page->page_url}}/{{strtolower($permission->page->page_name)}}.js"></script>
	@if (($permission->action_value & Action::$Add) || ($permission->action_value & Action::$Edit))
		<script src="{{URL::to('/')}}/assets/page/scripts/{{$permission->page->page_url}}/{{strtolower($permission->page->page_name)}}_form.js"></script>
	@endif
@endforeach