{{--calling_modal--}}
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="calling_modal_content">
        <div class="calling_modal_header">
            <p class="calling_right_close" data-dismiss="modal" onclick="calling_modal_close()">
                <img src="{{ asset('img/close.png') }}" style="width: 45px;height: 45px;">
            </p>
        </div>
        <div class="calling_modal_body">
            <input type="hidden" name="" id="attend_info" value="{{ $attend_info }}">

            @foreach($attend_info as $key => $attend)
                @if($attend->attended_time == '')
                    <div class="border_line">
                        <p class="cl_mdl_tb" style="background: #FAA50E;">
                            @if(strlen($attend->table_name) > 9)
                                {{ substr($attend->table_name, 0, 9)."..." }}
                            @else
                                {{ $attend->table_name }}
                            @endif
                        </p>
                        <p class="cl_mdl_tx"> 呼び出し中です。 </p>
                        <p class="cl_mdl_sec" style="background: #FF0000;color: white;" id="time1_{{$key}}">&nbsp;</p>
                        <p class="cl_mdl_at" style="background: #FF0000;" onclick="attend({{ $attend->calling_table_id }})">呼び出しを消す
                            <span class="glyphicon glyphicon-forward forward_icon"></span>
                        </p>
                    </div>
                {{--@else--}}
                    {{--<div class="border_line">--}}
                        {{--<p class="cl_mdl_tb" style="background: #CDCDCD;">--}}
                            {{--@if(strlen($attend->table_name) > 9)--}}
                                {{--{{ substr($attend->table_name, 0, 9)."..." }}--}}
                            {{--@else--}}
                                {{--{{ $attend->table_name }}--}}
                            {{--@endif--}}
                        {{--</p>--}}
                        {{--<p class="cl_mdl_tx"> is waiting for assistance for </p>--}}
                        {{--<p class="cl_mdl_sec" style="background: #CDCDCD;" id="time1_{{$key}}">{{ $attend->attended_time }} sec</p>--}}
                        {{--<p class="cl_mdl_at" style="background: #000000;">Attended.</p>--}}
                    {{--</div>--}}
                @endif
            @endforeach
        </div>
    </div>

</div>

<script>

    var parentURL = window.parent.location.href;

    //modal_close
    function calling_modal_close()
    {
        $("#CallingModal").modal("hide");
        var call_bell_img = document.getElementById('calling_bell');
        {{--call_bell_img.src = '{{ asset('img/calling_bell.png') }}';--}}
        window.location.replace(parentURL);
    }

    //timer part
    var myVar = setInterval(myTimer, 1000);
    function myTimer() {

        var attend_info =  $('#attend_info').val();
        attend_info = JSON.parse(attend_info);
        // console.log(attend_info);
        var current_time =  new Date();
        var calling_time = '';
        var elapsed_time = '';
        for(var i=0;i<attend_info.length;i++){
            if(attend_info[i].attended_time == '') {
                //calling_time = new Date(attend_info[i].calling_time);
                var dateParts = attend_info[i].calling_time.substr(0,10).split('-');
                var timePart = attend_info[i].calling_time.substr(11);
                calling_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
                calling_time = new Date(calling_time);

                elapsed_time = (current_time.getTime() - calling_time.getTime())/1000;
                elapsed_time = Math.round(elapsed_time);
                document.getElementById("time1_" + i).innerHTML = elapsed_time + ' 秒';
            }
            // else {
            //     document.getElementById("time1_" + i).innerHTML = attend_info[i].attended_time + ' sec';
            // }

        }
    }

</script>
