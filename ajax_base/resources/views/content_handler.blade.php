<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    @yield('page_header')
</h3>
<div class="page-bar">
        <ul class="page-breadcrumb">
                <li>
                        <i class="fa fa-home"></i>
                        <a class="page_switcher" href="{{URL::to('/')}}">Home</a>
                        <i class="fa fa-angle-right"></i>
                </li>
                @yield('breadcrumb')
        </ul>
</div>
<!-- END PAGE HEADER-->
@yield('content')

<!--Modal Start-->
<div class="modal fade" id="form_ajax" role="dialog" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <img src="{{URL::to('/')}}/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!--Modal End-->

<!--Modal Start-->
<div class="modal fade" id="info_ajax" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <img src="{{URL::to('/')}}/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<!--Modal End-->

@yield('init_js')