<!-- @foreach(App\Models\ACL\User::find(Auth::id())->group->permission->groupBy('module_id') as $permissions)
    <?php if ($permissions[0]->module_id == 2){ ?>
        @foreach($permissions as $permission)
        <li>
            <a class="page_switcher" href="{{URL::to($permission->page->page_url)}}">
                <i class="{{$permission->page->icon}}"></i>
                <span class="title">
                    {{$permission->page->page_name}}
                </span>
            </a>
        </li>
        @endforeach
    <?php } ?>
@endforeach -->
<?php $orders = array(3,4,2,1); ?>
@foreach($orders as $order)
    @foreach(App\Models\ACL\User::find(Auth::id())->group->permission->groupBy('module_id') as $permissions)
        <?php if ($permissions[0]->module_id == $order){ ?>
            <li>
                <a href="javascript:;">
                    <i class="{{$permissions[0]->module->icon}}"></i>
                    <span class="title">
                        {{$permissions[0]->module->module_name}}
                    </span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    @foreach($permissions as $permission)
                    <li>
                        <a class="page_switcher" href="{{URL::to($permission->page->page_url)}}">
                            <i class="{{$permission->page->icon}}"></i>
                            {{$permission->page->page_name}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
        <?php } ?>
    @endforeach
@endforeach