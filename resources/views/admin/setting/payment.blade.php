@extends('admin.setting')

@section('setting')

<div class="col-9 pl-0" style="margin-top: 100px;">
    <h5 class="black-text pl-5"><span class=" font-weight-bold fs-25">{{__('setting.Payment_Methods_MAX5')}}</span></h5>
    <div  id="sortable_div">
    <form action="{{ route('admin.setting.payment.post') }}" method="POST" id="post_form">
    <ul id="sortable" style="padding:0px">
        @foreach ($payments as $p)
        <li style="list-style-type:none" class="element">
            <div class="row pt-1 ml-4 pl-1">
                <div class="col-9">
                    <div class="card pt-1 pb-1 mt-3 pl-2">
                        <h5 class="font-weight-bold"><span class="fa fa-navicon name fs-20" style="padding-top: 9px;">&nbsp;&nbsp;{{ $p->name }}</span></h5>
                        <input type="hidden" class="post" name="orgitem" value="{{ $p->id }}">
                        <input type="hidden" class="sort" name="sort[]" value="{{ $p->id }}_{{ $p->sort }}">
                    </div>
                </div>
                <div class="col-3 pt-2 mt-1">
                    <button type="button" class="btn black radius-1 ptb pr-3 pl-3 deleteBtn" style="margin-top: 2px;" data-id="{{ $p->id }}">
                        <h6 class="mb-0 font-weight-bold fs-20">{{__('admin.Common.Delete')}}</h6>
                    </button>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    @csrf
    </form>
    </div>
    <div class="row mt-4 ml-5 pl-2">
        <div class="col-9">
        </div>
        <div class="col-3">
            <button type="button" class="btn bg-info radius-1 ptb pr-3 pl-3" style="margin-left:2px;" onclick="onadd()">
                <h5 class="mb-0 pr-1 pl-1 font-weight-bold fs-20">{{__('admin.Common.Add')}}</h5>
            </button>
        </div>
    </div>

    <li style="list-style-type:none; display:none" id="clone" class="element">
        <div class="row pt-2 ml-4 pl-2">
            <div class="col-9">
                <div class="card pt-1 pb-1 mt-3 pl-2">
                    <h5 class="font-weight-bold"><span class="fa fa-navicon name fs-20" style="padding-top: 9px;">&nbsp;&nbsp;</span></h5>
                    <input type="hidden" class="post" name="new[]" value="new">
                    <input type="hidden" class="sort" name="sort[]" value="">
                </div>
            </div>
            <div class="col-3 pt-2 mt-1">
                <button class="btn black radius-1 ptb pr-5 pl-5" style="margin-top: 2px;">
                    <h6 class="mb-0 font-weight-bold fs-20">{{__('admin.Common.Delete')}}</h6>
                </button>
            </div>
        </div>
    </li>
    {{--<div class="col-lg-12 mt-5 pr-4 text-right">--}}
        {{--<a href="#" class="btn bg-white black-text pt-2 pb-2 pr-2 pl-2"><h5 class="black-text mb-0">{{__('admin.Common.Cancel')}}</h5></a>--}}
        {{--<a href="#" class="btn bg-info black-text pt-2 pb-2 pr-2 pl-2"><h5 class="white-text mb-0" onclick="onapply()">{{__('admin.Common.Apply')}}</h5></a>--}}
    {{--</div>--}}

    <div class="col-lg-12 mt-1 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.payment') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-20">
                <b>{{__('admin.Common.Cancel')}}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-20" onclick="onApply()">
                <b>{{__('admin.Common.Apply')}}</b>
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
<script>
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();

    function onadd(){
        if($('.element:visible').length >= 5){
            //alert("You can't register over 5.");
            $("#alert-string")[0].innerText = "5つ以上登録することはできません。";
            $("#java-alert").modal('toggle');
            return;
        }
        var payment_name = prompt('Please enter payment type');
        if(payment_name == null || payment_name == "") return;
        var div = $('#clone').clone();
        div.show();
        $('.name', div).html("&nbsp;&nbsp;" + payment_name);
        $('.post', div).val(payment_name);
        $('#sortable').append(div);
    }

    $('.deleteBtn').on('mousedown', function(){
        var id = $(this).data('id');
        if(id > 0){
            var parent = $(this).closest('.element');
            parent.hide();
            var name_edit = $('.post', parent);
            name_edit.attr('name', 'removed[]');
            name_edit.attr('value', id);
        }
        else
            $(this).closest('.element').remove();
    });

    function onApply(){
        var index = 1;
        $('.element').each(function(i, obj){
            var visible = $(obj).is(':visible');
            if(visible){
                var id = $('.post', obj).val();
                console.log(id);
                $('.sort', obj).val(id + "_" + index);
                index++;
            } else {
                $('.sort', obj).attr('name', '');
            }
        });
        $('#post_form').submit();
    }
</script>
@endsection
