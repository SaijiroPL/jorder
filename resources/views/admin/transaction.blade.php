@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<div style="padding-top:8%"></div>
<div class="container-fluid">
    <div class="container blackgrey pt-3">
        <div class="row">
            <div class="col-6">
                <label class="text-white txtdemibold">Transactions</label>
            </div>
            <div class="col-6">
                <a>
                    <span class="">
                        <img src="img/Group 826.png" height="18" class="float-right" width="19" />
                    </span>
                </a>
            </div>
        </div>
        <div class="row mb-5 mt-5">
            <div class="col-12">
                <img src="img/Path 501.png" class="mb-2" height="25"/><label class="text-white ml-3 mr-3 font-weight-light fs-4 pt-2">31 MAY 2018</label>
                <img src="img/Path 502.png" class="mb-2" height="25" />
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table text-white txtdemibold">
                    <thead>
                        <tr>
                            <th class="border-0 fs-4" scope="col">
                                Time
                                <img src="img/Path 444.png" height="20" />
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

                        <tr>


                            <td >09:35 PM</td>
                            <td >A-4</td>
                            <td >$ 456.78</td>
                            <td>Nishikian Japanese Restaurant</td>
                            <td ><button class="outline-0 repbtn">REPRINT</button></td>


                        </tr>

                        <tr>


                            <td >09:35 PM</td>
                            <td >C-2</td>
                            <td >$ 550.00</td>
                            <td >Ms Naomi Osaka</td>
                            <td ><button class="outline-0 repbtn">REPRINT</button></td>


                        </tr>




                    </tbody>
                </table>
            </div>
        </div>


        <!-- Default switch -->
        <!--<label class="bs-switch">
            <input type="checkbox">
            <span class="slider round"></span>
        </label>-->
    </div>
</div>
@endsection
