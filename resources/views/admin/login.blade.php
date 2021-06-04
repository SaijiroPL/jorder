<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Restaurant</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: white;
        opacity: 1; /* Firefox */
    }
    body,.hh,html {
        min-height: 100vh;
    }
    .black{
        background:#2d2d2d !important;
    }
</style>
<body>
    <form method="POST" action="{{ route('admin.login', ['slag'=>$slag]) }}">
    @csrf
        <div class="container-fluid hh black lg-height">
            <div class="container pt-5">
                <div class="row pt-5 mt">
                    <div class="col-4 pt-5" style="height: 650px;">
                        <img class="lg-img-ht mt-auto mb-auto" src="{{ asset('receipt/'.$profile->logo_image) }}" />
                    </div>
                    <div class="col-8 pt-5" style="height: 650px;">
                        <h1 class="white-text mb-5 mt-0 pt-0 font-weight-bold">
                            @if($slag == 'setting')
                                {{ __('menu.Setting') }}
                            @elseif($slag == 'edit_menu')
                                {{ __('menu.Edit_Menu') }}
                            @elseif($slag == 'saledata')
                                {{ __('menu.Sales_Data') }}
                            @elseif($slag == 'table')
                                {{ __('menu.Table_Edit') }}
                            @endif
                        </h1>
                        <input type="password" name="password"
                               style="width:350px;border:2px solid white !important;text-align:center;color:white !important;font-size:25px"
                               placeholder="" />
                        <div class="row" style="padding-top:20rem">
                            <div class="col-6">
                                <a class="btn white w-100" onclick="window.history.back()">
                                    <h5 class="mb-0 black-text font-weight-bold fs-30">{{ __('auth.Cancel') }}</h5>
                                </a>
                            </div>
                            <div class="col-6 pl-2">
                                <button class="btn bg-info w-100">
                                    <h5 class="mb-0 white-text font-weight-bold fs-30">{{ __('auth.Log_In') }}</h5>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    @if($alert)
        <div class="alert alert-success alert-dismissible lg-alert" id="success-alert" role="alert" style="margin: -550px 0 0 510px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            {{ $alert }}
        </div>
        <br>
    @endif
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{ asset('js/mdb.min.js') }}"></script>

    <script>
        $("#success-alert").fadeTo(3000, 500).fadeOut(5000, function(){
            $("#success-alert").alert('close');
        });
    </script>
</body>

<style>
    .adm_inp {
        width:350px;
        border:2px solid white !important;
        text-align:center;
        color:white !important;
        font-size:20px;
    }
</style>
