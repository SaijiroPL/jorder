@extends('layout.admin_layout')

@section('title', 'Settings')

@section('content')
<div class="blackgrey pl-xl-4" style="height:100vh">
    <div style="padding-top:10%"></div>
    <div class="widthh white mr-lg-auto ml-auto ml-xl-5 hidialog review_content" style="width:90% !important;height:665px;overflow-y:auto">
        <div class="row">
            <div class="col-12 close-btn-review">
                <a>
                    <span class="">
                        <a href="{{route('admin.saledata')}}">
                            <img src="{{ asset('img/Group1100.png') }}" height="20" class="float-right" width="20" />
                        </a>
                    </span>
                </a>
            </div>
        </div>
        <div class="row pt-2" style="background:#ededed;">
            <div class="col-5">
                <span class="red-text h4-responsive font-weight-normal">0 min</span>
                <img src="{{ asset('img/chatorange.png') }}" width="30" class="mr-5 ml-5" />
                <span class="fa fa-user ml-4 pl-3 h3-responsive font-weight-normal"> 1</span>
                <br>
                <button class="font-weight-bold float-left mr-4" style="height:60px;width:100px"><h4 class="mb-0 font-weight-bold">A-1</h4></button>
                <div style="width:7rem;word-wrap:break-word" class="float-left pt-2 font-weight-bold">Walk-in 7 &nbsp;&nbsp;&nbsp; 7:30 PM</div>
                <img src="{{ asset('img/chat1.png') }}" class="ml-3 mt-3" />
                <br>
                <br>
            </div>
            <div class="col-3 text-right pt-2">
                <img src="{{ asset('img/chatorange.png') }}" class="" />
                <p class="font-weight-bold">Review</p>
            </div>
            <div class="col-4 text-right">
                <img src="{{ asset('img/sad1.png') }}" class="" />
                <img src="{{ asset('img/normal1.png') }}" class="" />
                <img src="{{ asset('img/hap1.png') }}" class="" />
            </div>
            <textarea class="mr-3 white mb-2 ml-3" style="height:80px;width:100%;border:1px solid grey;"></textarea>
        </div>
        <div class="row pt-2" style="background:#d8d8d8;">
            <div class="col-5">
                <span class="red-text h4-responsive font-weight-normal">0 min</span>
                <img src="{{ asset('img/chat5.png') }}" width="40" class="mr-5 ml-5" />
                <span class="fa fa-user ml-4 pl-2 h3-responsive font-weight-normal"> 1</span>
                <br>
                <button class="font-weight-bold float-left mr-4" style="height:60px;width:100px"><h4 class="mb-0 font-weight-bold">A-1</h4></button>
                <div style="width:7rem;word-wrap:break-word" class="float-left pt-2 font-weight-bold">John Donowoe 7:30PM</div>
                <img src="{{ asset('img/chat2.png') }}" class="ml-4 mt-3" />
                <br>
                <br>
            </div>
            <div class="col-3 text-right pt-2">
                <img src="{{ asset('img/chat3.png') }}" class="" />
                <p class="font-weight-bold">Review</p>
            </div>
            <div class="col-4 text-right">
                <img src="{{ asset('img/sad2.png') }}" class="" />
                <img src="{{ asset('img/normal2.png') }}" class="" />
                <img src="{{ asset('img/hap2.png') }}" class="" />
            </div>
            <textarea class="mr-3 white mb-2 ml-3" style="height:80px;width:100%;border:1px solid grey;"></textarea>
        </div>
        <div class="row pt-2" style="background:#ededed;">
            <div class="col-5">
                <span class="red-text h4-responsive font-weight-normal">0 min</span><img src="{{ asset('img/chatorange.png') }}" width="30" class="mr-5 ml-5" /> <span class="fa fa-user ml-4 pl-3 h3-responsive font-weight-normal"> 1</span>
                <br>
                <button class="font-weight-bold float-left mr-4" style="height:60px;width:100px"><h4 class="mb-0 font-weight-bold">A-1</h4></button>
                <div style="width:7rem;word-wrap:break-word" class="float-left pt-2 font-weight-bold">Walk-in 7 &nbsp;&nbsp;&nbsp; 7:30 PM</div>
                <img src="{{ asset('img/chat1.png') }}" class="ml-3 mt-3" />
                <br>
                <br>
            </div>
            <div class="col-3 text-right pt-2">
                <img src="{{ asset('img/chatorange.png') }}" class="" />
                <p class="font-weight-bold">Review</p>
            </div>
            <div class="col-4 text-right">
                <img src="{{ asset('img/sad3.png') }}" class="" />
                <img src="{{ asset('img/normal3.png') }}" class="" />
                <img src="{{ asset('img/hap3.png') }}" class="" />
            </div>
            <textarea class="mr-3 white mb-2 ml-3" style="height:80px;width:100%;border:1px solid grey;"></textarea>
        </div>
        <div class="row pt-2" style="background:#d8d8d8;">
            <div class="col-5">
            <span class="red-text h4-responsive font-weight-normal">0 min</span>
            <img src="{{ asset('img/chat5.png') }}" width="40" class="mr-5 ml-5" />
            <span class="fa fa-user ml-4 pl-2 h3-responsive font-weight-normal"> 1</span>
            <br>
            <button class="font-weight-bold float-left mr-4" style="height:60px;width:100px"><h4 class="mb-0 font-weight-bold">A-123</h4></button>
            <div style="width:7rem;word-wrap:break-word" class="float-left pt-2 font-weight-bold">John Donowoe 7:30PM</div>
                <img src="{{ asset('img/chat2.png') }}" class="ml-4 mt-3" />
                <br>
                <br>
            </div>
            <div class="col-3 text-right pt-2">
                <img src="{{ asset('img/chat3.png') }}" class="" />
                <p class="font-weight-bold">Review</p>
            </div>
            <div class="col-4 text-right">
                <img src="{{ asset('img/sad2.png') }}" class="" />
                <img src="{{ asset('img/normal2.png') }}" class="" />
                <img src="{{ asset('img/hap2.png') }}" class="" />
            </div>
            <textarea class="mr-3 white mb-2 ml-3" style="height:80px;width:100%;border:1px solid grey;"></textarea>
        </div>
    </div>
</div>
@endsection
