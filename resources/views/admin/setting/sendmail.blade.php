@extends('admin.setting')

@section('setting')
    <div class="col-9 pl-0" style="margin-top: 100px;">
        <h5 class="black-text pl-5 pbb" style="padding-bottom: 0%;"><span class="font-weight-bold fs-30">Mail List</span></h5>
        <form action="{{ route('admin.setting.sendmail.post') }}" method="POST" id="post_form">
            <div id="content" style="height: 500px;overflow-x: hidden;overflow-y: auto;">
                @foreach($mails as $mail)
                    <div class="row ml-4 pl-2 element">
                        <div class="col-9 pt-2">
                            <input class="card pt-2 pb-2 pl-4 pr-4 font-weight-bold name fs-25" style="width:100%;" value="{{ $mail->name }}" name="orgitem">
                        </div>
                        <div class="col-3 pt-2">
                            <button type="button" class="btn black radius-1 ptb pr-5 pl-5 delete-btn" data-id="{{ $mail->id }}" onclick="deleteGroup(this)">
                                <h6 class="mb-0 font-weight-bold fs-25">Delete</h6>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @csrf
        </form>

        <div class="row ml-4 pl-2 element" id="original" style="display:none">
            <div class="col-9 pt-2">
                <input class="card pt-2 pb-2 pl-4 pr-4 font-weight-bold name fs-25" style="width:100%" name="new[]">
            </div>
            <div class="col-3 pt-2">
                <button class="btn black radius-1 ptb pr-5 pl-5" data-id="0" onclick="deleteGroup(this)">
                    <h6 class="mb-0 font-weight-bold fs-25">Delete</h6>
                </button>
            </div>
        </div>

        <div class="row mt-2 ml-4 pl-2">
            <div class="col-9 pt-2">
                <input class="card pt-2 pb-2 pl-4 pr-4 font-weight-bold addinput fs-25" style="width:100%">
            </div>
            <div class="col-3 pt-2">
                <button class="btn black radius-1 ptb pr-4 pl-4" style="width: 185px;" onclick="addGroup()">
                    <h6 class="mb-0 font-weight-bold fs-25">Add</h6>
                </button>
            </div>
        </div>

        <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
            <a href="{{ route('admin.setting.sendmail') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
                <h5 class="black-text mb-0 fs-25">
                    <b>Cancel</b>
                    <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
                </h5>
            </a>
            <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
                <h5 class="white-text mb-0 fs-25" onclick="onApply()">
                    <b>Apply</b>
                    <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
                </h5>
            </a>
        </div>
    </div>
    <script>
        function addGroup()
        {
            var name = $('.addinput').val();
            if(name == "") return;
            var div = $('#original').clone();
            $(div).show();
            $('.name', div).val(name);
            $('#content').append(div);
            $('.addinput').val('');
        }
        function deleteGroup(obj){
            var id = $(obj).data('id');
            if(id > 0){
                var parent = $(obj).closest('.element');
                parent.hide();
                var name_edit = $('.name', parent);
                name_edit.attr('name', 'removed[]');
                name_edit.attr('value', id);
            }
            else
                $(obj).closest('.element').remove();
        }
        function onApply(){
            $('#post_form').submit();
        }
    </script>
@endsection
