<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky bg-default left-auto top-1" id="navbarBlur"
    data-scroll="true">
    <div class="container-fluid py-1 px-3">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="text-white" href="javascript:;">
                        <i class="ni ni-box-2"></i>
                    </a>
                </li>
                <li class="breadcrumb-item text-sm text-white"><a class="opacity-5 text-white"
                        href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">Default</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-white">Default</h6>
        </nav> --}}
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link p-0">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </a>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" >
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="{{ url('/') }}" target="_blank" class="nav-link text-white p-0" title="Go to Home">
                    <i class="fa fa-globe cursor-pointer"></i> Web
                </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="{{ route('cache.clear') }}" class="nav-link text-white p-0" title="Tap to cache clear">
                    <i class="fa fa-broom cursor-pointer"></i> Cache Clear
                </a>
            </li>
            @if(config('app.update_url_enable'))
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="{{ route('update.url') }}" class="nav-link text-white p-0" title="Tap to cache clear">
                    <i class="fa fa-sync cursor-pointer"></i> Update URL
                </a>
            </li>
            @endif
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="{{ url(config('log-viewer.route_path')) }}" class="nav-link text-white p-0" title="Tap to cache clear">
                    <i class="fa fa-bug cursor-pointer"></i> Error Logs
                </a>
            </li>
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                {{-- <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div> --}}
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ route('profile') }}"
                        class="nav-link text-white font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">{{ auth()->user()->name }}</span>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" onclick="$('#logout-form').submit()" class="nav-link text-white p-0" title="Tap to LogOut">
                        <i class="fa fa-arrow-right"></i> Log Out
                    </a>
                    <form action="{{ route('logout') }}" method="post" id="logout-form"> @csrf </form>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
