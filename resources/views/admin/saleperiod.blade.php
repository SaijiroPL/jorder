@extends('layout.admin_layout')

@section('title', 'Sales Data')

@section('content')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $( "#search_trans_date" ).datepicker({ dateFormat: 'yy年mm月dd日' });
    });
</script>
<div class="pbb1 blackgrey">

    <div style="height:85px;"></div>

    <div class="widthhh white pt-0 pb-0 " style="margin-right: auto;">

        <br><br>
        <form action="{{ route('admin.saledata.period') }}" style="text-align: center">
        <div class="row">
            <div class="col-12" style="margin:-40px 0 0 0">
                <a onclick="window.history.back()">
                    <span class="" style="position:relative;right:10px">
                        <img src="{{ asset('img/Group1100.png') }}" width="25" height="25" class="float-right" style="margin-top:12px;" />
                    </span>
                </a>

                <div class="float-right">
                    <input type="text" id="search_trans_date" style="height: 2rem; width:200px" class="border-blue mt-5" name="date" onchange="submit()" value="{{ $outputDate }}"/>
                    <select class="border-blue w-200px heigh2rem fs-20 mt-5" id="period" name="mode" onchange="submit()">
                        {{--<option value="0">Select Type</option>--}}
                        <option value="daily" @if($mode == 'daily') selected @endif>{{ __('admin.SALE.Day') }}</option>
                        <option value="weekly" @if($mode == 'weekly') selected @endif>{{ __('admin.SALE.Week') }}</option>
                        <option value="monthly" @if($mode == 'monthly') selected @endif>{{ __('admin.SALE.Month') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" style="padding-left: 10px;padding-right: 10px;">
            <div class="col-8">
                <label class="text-darkgrey1 mt-0 fs-25">{{ __('admin.SALE.SALE_REVIEW') }}</label>
                <br>
                <select class="border-blue w-200px heigh2rem fs-15" id="category" name="category" onchange="submit()">
                    <option value="">{{ __('admin.SALE.All') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if(isset($_GET['category']) && $_GET['category'] == $cat->id) selected @endif>{{ $cat->name_jp }}</option>
                    @endforeach
                </select>
                <select class="border-blue w-200px heigh2rem fs-15" id="subcategory" name="subcategory" onchange="submit()">
                    <option value="">{{ __('admin.SALE.All') }}</option>
                    @foreach($subcategories as $cat)
                    <option value="{{ $cat->id }}" @if(isset($_GET['subcategory']) && $_GET['subcategory'] == $cat->id) selected @endif>{{ $cat->name_jp }}</option>
                    @endforeach
                </select>
                <select class="border-blue w-200px heigh2rem fs-15" id="dish" name="dish" onchange="submit()">
                    <option value="">{{ __('admin.SALE.All') }}</option>
                    @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}" @if(isset($_GET['dish']) && $_GET['dish'] == $dish->id) selected @endif>{{ $dish->name_jp }}</option>
                    @endforeach
                </select>
                <table class="saletable mt-2">
                    <thead>
                        <tr class="text-center">
                            <th align="center">{{ __('admin.SALE.Date') }}</th>
                            <th>{{ __('admin.SALE.Sold') }}</th>
                            <th>{{ __('admin.SALE.Group') }}</th>
                            <th>{{ __('admin.SALE.Guest') }}</th>
                            <th>{{ __('admin.SALE.Order') }}</th>
                            <th>{{ __('admin.SALE.Call') }}</th>
                            <th>{{ __('admin.SALE.Feedback') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $dt => $value)
                        <tr class="cl_1">
                            <td width="25%">{{ $dt }}</td>
                            <td width="18%" align="right">{{ $value['sales'] }}</td>
                            <td width="12%" align="right">{{ $value['group'] }}</td>
                            <td width="10%" align="right">{{ $value['guest'] }}</td>
                            <td width="10%" align="right">{{ $value['orders'] }}</td>
                            <td width="10%" align="right">{{ $value['calls'] }}</td>
                            <td width="15%" align="right">{{ $value['feedback'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-3">
                <label class="text-darkgrey1 mt-0 fs-25">{{ __('admin.SALE.RANK_ASC') }}</label>
                <table class="bestsellerTable">
                    <tbody>
                        @for($i=0;$i<10;$i++)
                            @if($i < count($best_sellers))
                            <tr>
                                <td><span class="fs-23">{{ $i+1 }}</span></td>
                                <td><span class="fs-23">{{ $best_sellers[$i]['customer_name'] }}</span></td>
                                <td align="right"><span class="fs-23">{{ $best_sellers[$i]['sale'] }}</span></td>
                            </tr>
                            @else
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>

                <label class="text-darkgrey1 mt-1 fs-25">{{ __('admin.SALE.RANK_DESC') }}</label>
                <table class="bestsellerTable">
                    <tbody>
                    @for($i=0;$i<10;$i++)
                        @if($i < count($worst_sellers))
                            <tr>
                                <td><span class="fs-23">{{ $i+1 }}</span></td>
                                <td><span class="fs-23">{{ $worst_sellers[$i]['customer_name'] }}</span></td>
                                <td align="right"><span class="fs-23">{{ $worst_sellers[$i]['sale'] }}</span></td>
                            </tr>
                        @else
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endif
                    @endfor
                    </tbody>
                </table>
            </div>
        </div>
        </form>
    </div>
</div>

{{--<script>--}}
    {{--function onRow(){--}}
        {{--window.location = "{{ route('admin.review') }}";--}}
    {{--}--}}

{{--</script>--}}
<style>
    .saletable {
        border-collapse: collapse;
        margin-bottom: 20px;
        width: 100%;
    }
    table.saletable td, table.saletable th {
        border: 2px solid black;
        /*padding-left: 5px;*/
    }
    table.saletable td {
        padding-left: 10px;
        padding-right: 10px;
    }
    .cl_1:nth-child(2n){
        background-color: white;
    }
    .cl_1:nth-child(2n+1){
        background-color: lightgrey;
    }
    .cl_2:nth-child(2n){
        background-color: lightgrey;
    }
    .cl_2:nth-child(2n+1){
        background-color: white;
    }
</style>
@endsection
