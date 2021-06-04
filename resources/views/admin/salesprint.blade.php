@extends('layout.admin_layout')

@section('title', 'Sales Print')

@section('content')
<script type="text/javascript" src="{{ asset('js/jquery.printPage.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.btnprn').printPage();
    });
</script>

<div style="padding-top:8%"></div>
<div class="container-fluid">
    <div class="container blackgrey pt-3">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <a href="{{ url('admin/salesprint_preview') }}" class="text-white txtdemibold btnprn">Print Preview</a>
            </div>
            <div class="col-4">
                <a onclick="window.history.back()">
                    <span class="">
                        <img src="{{ asset('img/Group826.png') }}" width="25" height="25" class="float-right" />
                    </span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table text-white txtdemibold">
                    <thead>
                        <tr>
                            <th class="border-0 fs-4" scope="col">
                                Time
                            </th>
                            <th class="border-0 fs-4" scope="col">TABLE</th>
                            <th class="border-0 fs-4" scope="col">AMOUNT</th>
                            <th class="border-0 fs-4" scope="col">CUSTOMER</th>
                            <th class="border-0 fs-4" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-0">09:35 PM</td>
                            <td class="border-0">H-1 + H-2</td>
                            <td class="border-0">$ 235.80</td>
                            <td class="border-0">Ms Jenifer Lopez</td>
                            <td class="border-0"><button class="outline-0 repbtn">REPRINT</button></td>
                        </tr>
                        <tr>
                            <td >09:35 PM</td>
                            <td >A-1</td>
                            <td >$ 2,150.00</td>
                            <td >Walk-in 16</td>
                            <td ><button class="outline-0 repbtn">REPRINT</button></td>
                        </tr>
                        <tr>
                            <td >09:35 PM</td>
                            <td >B-3</td>
                            <td >$ 550.50</td>
                            <td >Walk-in 15</td>
                            <td ><button class="outline-0 repbtn">REPRINT</button></td>
                        </tr>
                        <tr>
                            <td >09:35 PM</td>
                            <td >B-4</td>
                            <td > $ 100.35</td>
                            <td >John</td>
                            <td ><button class="outline-0 repbtn">REPRINT</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

