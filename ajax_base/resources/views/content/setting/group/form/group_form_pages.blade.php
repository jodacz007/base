<div class="row">
    <div class="col-md-12">
        <div class="panel-group accordion">
            @foreach(App\Models\ACL\Permission::where('group_id',$group_id)->groupBy('module_id')->get() as $permission)
                <div id="module_page_container_{{$permission->module->module_id}}" class="panel panel-default" style="display:none">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" href="#module_{{$permission->module->module_id}}">
                                {{$permission->module->module_name}} Module
                            </a>
                        </h4>
                    </div>
                    <div id="module_{{$permission->module->module_id}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            Page Name
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach(App\Models\ACL\Permission::where('group_id',$group_id)->where('module_id',$permission->module->module_id)->get() as $permission_page)
                                    <tr>
                                        <td>
                                            {!!
                                                Form::checkbox('page_'.$permission_page->page->page_id.'[]',$permission_page->page->page_id,
                                                (isset($group_data)&&count($group_data->permission()->where('page_id',$permission_page->page->page_id)->get()))?true:false,
                                                array(
                                                    'class'=>'module_pages page_module_'.$permission->module->module_id,
                                                    'data-checkbox' => 'icheckbox_square-grey'
                                                ));
                                            !!}
                                            <b>{{$permission_page->page->page_name}}</b>
                                        </td>
                                        <td>
                                            @foreach(App\Models\ACL\Action::getActionList() as $id => $name)
                                                @if (($id & $permission_page->action_value) && $id != App\Models\ACL\Action::$View)
                                                    <label>
                                                    {!!
                                                        Form::checkbox('page_'.$permission_page->page->page_id.'_'.$permission->module->module_id.'[]',$id,
                                                        (isset($group_data)&&
                                                        count($group_data->permission()->where('page_id',$permission_page->page->page_id)->get())&&
                                                        ($group_data->permission()->where('page_id',$permission_page->page->page_id)->first()->action_value&$id))?true:false,
                                                        array(
                                                            'class'=>'module_page_'.$permission_page->page->page_id.' module_page_action_'.$permission->module->module_id,
                                                            'data-checkbox' => 'icheckbox_square-grey',
                                                            (isset($group_data)&&count($group_data->permission()->where('page_id',$permission_page->page->page_id)->get())==0)?'disabled':'' => ''
                                                        ));
                                                    !!}
                                                    {{$name}}
                                                    </label>
                                                    &nbsp;
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
    </div>
</div>
