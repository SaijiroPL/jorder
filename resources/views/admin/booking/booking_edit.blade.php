@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<link href="{{ asset('css/booking_edit.css') }}" rel="stylesheet">
<div class="p-4 mt-5">
    <div class="container-fluid pb-3 position-relative">
        <div class="bg-grey pt-2 pl-2">
            <ul class="nav uldesign">
                <li class="grey white pr-2">22 MAY<BR>10:15PM</li>
                <li class=""><img src="{{ asset('img/user.png') }}" class="mr-2" /> 12</li>
                <li class=""><img src="{{ asset('img/img2.png') }}" /> H-1 <img src="{{ asset('img/redplus.png') }}" /></li>
                <li class="pt-3">Ms Jenifer Lo</li>
                <li class=""><img src="{{ asset('img/notes.png') }}" class="mr-2" /> Notes</li>
            </ul>
        </div>
        <div class="container pt-5 mt-2">
            <div class="row">
                <div class="col-4">
                    <h3 class="font-weight-bold text-info">Today</h3>
                    <div id="datetimepicker12" class="w-100">
                </div>
            </div>
            <div class="col-4 text-center">
                <h3 class="font-weight-bold mb-3 text-left text-info" style="border-bottom:2px solid #1ec2c9;padding-bottom:11px">NOW</h3>
                <label class="light-text text-center ll">10:00 PM</label>
                <br>
                <label class="black-text w-100 text-center ll pt-1 pb-1" style="border-bottom:2px solid black;border-top:2px solid black">10:15 PM</label>
                <br>
                <label class="light-text text-center ll">10:30 PM </label>
                <br>
                <label class="light-text text-center ll">10:45 PM</label>
                <br>
                <label class="light-text text-center ll">11:00 PM</label>
                <br>
                <label class="light-text text-center ll">11:15 PM</label><br>
                <label class="light-text text-center ll">11:30 PM</label><br>
                <label class="light-text text-center ll">11:45 PM</label><br>
                <label class="light-text text-center ll">12:00 AM</label><br>
                <label class="light-text text-center ll">12:30 AM</label><br>
            </div>
            <div class="col-4 text-center">
                <h3 class="font-weight-bold mb-3 text-left text-info" style="border-bottom:2px solid #1ec2c9;padding-bottom:11px">30 min</h3>
                <label class="light-text text-center ll">テイクアウト</label>
                <br>
                <label class="black-text w-100 text-center ll pt-1 pb-1" style="border-bottom:2px solid black;border-top:2px solid black">30 min</label>
                <br>
                <label class="light-text text-center ll">60 min</label>
                <br>
                <label class="light-text text-center ll">90 min</label>
                <br>
                <label class="light-text text-center ll">120 min</label>
                <br>
                <label class="light-text text-center ll">無制限</label>
            </div>
        </div>
        <div class="row text-right mm" >
            <div class="offset-2 col-10">
                <a href="#" class="btn black"><h4 class="mb-0 font-weight-bold">CANCEL<img src="{{ asset('img/Group728white.png') }}" /></h4></a>
                <a href="#" class="btn red"><h4 class="mb-0 font-weight-bold">APPLY <img src="{{ asset('img/Group728white.png') }}" /></h4></a>
            </div>
        </div>
    </div>
</div>
@endsection
