@extends('admin.setting')

@section('setting')
<style>
    .p-edit-oneline{
        /*padding-top: 8px;*/
    }
</style>
<div class="col-9 pl-5" style="margin-top: 100px;">
    <h5 class="black-text font-weight-bold fs-25">{{__('setting.Time_Slots')}}</h5>

    <form class="holiday_form" action="{{ route('admin.setting.timeslots.post') }}" method="POST" id="post_form">

    <h6 class="text-info font-weight-bold mt-3 fs-20">{{__('setting.Regular_Time_Slots')}}</h6>

    <div class="card ml-1 col-lg-11 pr-0">
        <div class="row mt-2 mb-4">
            <div class="col-4"></div>
            <div class="col-3 text-info fs-20">{{__('admin.Discount.START')}}</div>
            <div class="col-3 text-info fs-20">{{__('admin.Discount.END')}}</div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-4">
                <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[0]->morning_on == "0")
                        light-text
                    @endif
                 fs-20">{{__('setting.Morning')}}</p>
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-morning-start" value="{{ $slots[0]->morning_starts }}"
                @if($slots[0]->morning_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-morning-end" value="{{ $slots[0]->morning_ends }}"
                @if($slots[0]->morning_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-2 pl-0">
                <label class="switch">
                    <input type="checkbox" class="time-check" name="regular-morning-on"
                    @if($slots[0]->morning_on == "1")
                        checked
                    @endif
                    >
                    <span class="slider round check-round"></span>
                </label>
            </div>
        </div>
        <div class="col-lg-12"><hr class=""></div>
        <div class="row">
            <div class="col-4">
                <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[0]->lunch_on == "0")
                        light-text
                    @endif
                fs-20">{{__('setting.Lunch')}}</p>
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-lunch-start" value="{{ $slots[0]->lunch_starts }}"
                @if($slots[0]->lunch_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-lunch-end" value="{{ $slots[0]->lunch_ends }}"
                @if($slots[0]->lunch_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-2 pl-0">
                <label class="switch">
                    <input type="checkbox" class="time-check" name="regular-lunch-on"
                    @if($slots[0]->lunch_on == "1")
                        checked
                    @endif
                    >
                    <span class="slider round check-round"></span>
                </label>
            </div>
        </div>
        <div class="col-lg-12"><hr class=""></div>
        <div class="row">
            <div class="col-4">
                <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[0]->tea_on == "0")
                        light-text
                    @endif
                fs-20">{{__('setting.Tea')}}</p>
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-tea-start" value="{{ $slots[0]->tea_starts }}"
                @if($slots[0]->tea_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-tea-end" value="{{ $slots[0]->tea_ends }}"
                @if($slots[0]->tea_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-2 pl-0">
                <label class="switch">
                    <input type="checkbox" class="time-check" name="regular-tea-on"
                    @if($slots[0]->tea_on == "1")
                        checked
                    @endif
                    >
                    <span class="slider round check-round"></span>
                </label>
            </div>
        </div>
        <div class="col-lg-12"><hr class=""></div>
        <div class="row">
            <div class="col-4">
                <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[0]->dinner_on == "0")
                        light-text
                    @endif
                fs-20">{{__('setting.Dinner')}}</p>
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-dinner-start" value="{{ $slots[0]->dinner_starts }}"
                @if($slots[0]->dinner_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-dinner-end" value="{{ $slots[0]->dinner_ends }}"
                @if($slots[0]->dinner_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-2 pl-0">
                <label class="switch">
                    <input type="checkbox" class="time-check" name="regular-dinner-on"
                    @if($slots[0]->dinner_on == "1")
                        checked
                    @endif
                    >
                    <span class="slider round check-round"></span>
                </label>
            </div>
        </div>
        <div class="col-lg-12"><hr class=""></div>
        <div class="row">
            <div class="col-4">
                <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[0]->latenight_on == "0")
                        light-text
                    @endif
                fs-20">{{__('setting.Late_Night')}}</p>
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-latenight-start" value="{{ $slots[0]->latenight_starts }}"
                @if($slots[0]->latenight_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-3 text-right pr-5">
                <input type="text" class="time-element" style="font-size: 20px;" name="regular-latenight-end" value="{{ $slots[0]->latenight_ends }}"
                @if($slots[0]->latenight_on == "0")
                    disabled
                @endif
                >
            </div>
            <div class="col-2 pl-0">
                <label class="switch">
                    <input type="checkbox" class="time-check" name="regular-latenight-on"
                    @if($slots[0]->latenight_on == "1")
                        checked
                    @endif
                    >
                    <span class="slider round check-round"></span>
                </label>
            </div>
        </div>
        <div class="col-lg-12"><hr class=""></div>
    </div>

    <h6 class="text-info font-weight-bold mt-3 fs-20">{{__('setting.Particular_Time_Slots')}}</h6>

    @php
        $name_array = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $mark_array = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    @endphp
    @foreach($name_array as $i => $key)
    <div class="card ml-1 mt-3 pt-3 pb-2 col-lg-11 pr-0 particular">
        <div class="row">
            <div class="col-7 pl-4">
                <h5 class="font-weight-normal fs-20">{{ __('setting.'.$mark_array[$i]) }}</h5>
            </div>
            <div class="col-3 pr-0 text-right" style="margin-left: 16px;">
                <label class="switch" style="margin-right: 10px;">
                    <input type="checkbox" class="accordion-check" name="{{ $key }}-on"
                        @if($slots[$i + 1]->day_on == "1")
                            checked
                        @endif
                    style="width:0px; height: 0px;">
                    <span class="slider round top-round"></span>
                </label>
            </div>
        </div>
        <div class="accordion-div" style="display:
            @if($slots[$i + 1]->day_on == "1")
                block
            @else
                none
            @endif
            ">
            @if($key == 'saturday' || $key == 'sunday')
            <div class="row">
                <div class="col-7">
                    <h6 class="font-weight-normal light-text pl-2 fs-20">{{__('setting.Non_Businees_Day')}}</h6>
                </div>
                <div class="col-4 pr-0 text-right" style="margin-left: 16px;">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-business-on"
                        @if($slots[$i + 1]->non_business == "1")
                            checked
                        @endif
                        style="width:0px; height: 0px;">
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            @endif
            <div class="row mt-2 mb-4">
                <div class="col-4"></div>
                <div class="col-3 text-info fs-20">{{__('admin.Discount.START')}}</div>
                <div class="col-3 text-info fs-20">{{__('admin.Discount.END')}}</div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-4">
                    <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[$i + 1]->morning_on == "0")
                        light-text
                    @endif
                    fs-20">{{__('setting.Morning')}}</p>
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-morning-start" value="{{ $slots[$i + 1]->morning_starts }}"
                    @if($slots[$i + 1]->morning_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-morning-end" value="{{ $slots[$i + 1]->morning_ends }}"
                    @if($slots[$i + 1]->morning_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-2 pl-0">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-morning-on" class="time-check"
                            @if($slots[$i + 1]->morning_on == "1")
                                checked
                            @endif
                        >
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-12"><hr class=""></div>
            <div class="row">
                <div class="col-4">
                    <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[$i + 1]->lunch_on == "0")
                        light-text
                    @endif
                    fs-20">{{__('setting.Lunch')}}</p>
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-lunch-start" value="{{ $slots[$i + 1]->lunch_starts }}"
                    @if($slots[$i + 1]->lunch_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-lunch-end" value="{{ $slots[$i + 1]->lunch_ends }}"
                    @if($slots[$i + 1]->lunch_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-2 pl-0">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-lunch-on" class="time-check"
                        @if($slots[$i + 1]->lunch_on == "1")
                            checked
                        @endif
                        >
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-12"><hr class=""></div>
            <div class="row">
                <div class="col-4">
                    <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[$i + 1]->tea_on == "0")
                        light-text
                    @endif
                    fs-20">{{__('setting.Tea')}}</p>
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-tea-start" value="{{ $slots[$i + 1]->tea_starts }}"
                    @if($slots[$i + 1]->tea_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-tea-end" value="{{ $slots[$i + 1]->tea_ends }}"
                    @if($slots[$i + 1]->tea_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-2 pl-0">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-tea-on" class="time-check"
                        @if($slots[$i + 1]->tea_on == "1")
                            checked
                        @endif
                        >
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-12"><hr class=""></div>
            <div class="row">
                <div class="col-4">
                    <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[$i + 1]->dinner_on == "0")
                        light-text
                    @endif
                    fs-20">{{__('setting.Dinner')}}</p>
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-dinner-start" value="{{ $slots[$i + 1]->dinner_starts }}"
                    @if($slots[$i + 1]->dinner_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-dinner-end" value="{{ $slots[$i + 1]->dinner_ends }}"
                    @if($slots[$i + 1]->dinner_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-2 pl-0">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-dinner-on" class="time-check"
                        @if($slots[$i + 1]->dinner_on == "1")
                            checked
                        @endif
                        >
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-12"><hr class=""></div>
            <div class="row">
                <div class="col-4">
                    <p class="mb-0 pl-3 p-edit-oneline
                    @if($slots[$i + 1]->latenight_on == "0")
                        light-text
                    @endif
                    fs-20">{{__('setting.Late_Night')}}</p>
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-latenight-start" value="{{ $slots[$i + 1]->latenight_starts }}"
                    @if($slots[$i + 1]->latenight_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-3 text-right pr-5">
                    <input type="text" class="time-element" style="font-size: 20px;" name="{{ $key }}-latenight-end" value="{{ $slots[$i + 1]->latenight_ends }}"
                    @if($slots[$i + 1]->latenight_on == "0")
                        disabled
                    @endif
                    >
                </div>
                <div class="col-2 pl-0">
                    <label class="switch">
                        <input type="checkbox" name="{{ $key }}-latenight-on" class="time-check"
                        @if($slots[$i + 1]->latenight_on == "1")
                            checked
                        @endif
                        >
                        <span class="slider round check-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-12"><hr class=""></div>
        </div>
    </div>
    @endforeach
    @csrf
    </form>
    <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.timeslots') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
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
<div class="modal fade" id="java-alert1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -50px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="alert-string1" class="text-center fs-20"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                    {{ __('admin.Common.Close') }}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="search_data" value="{{ $search_data }}">
<script type="text/javascript" src="{{ asset('js/timepicki.js') }}"></script>
<script>
    $(document).ready(function(){
        var data = $("#search_data").val();
        if(data != "")
        {
            $("#alert-string1")[0].innerText = data;
            $("#java-alert1").modal('toggle');
        }

        //$(".time-element").timepicki({start_time: ["06", "00", "AM"]});
        $(".time-element").each(function(i, obj){
            var init_time = $(obj).val();
            $(obj).timepicki({start_time: [init_time.substring(0, 2), init_time.substring(3, 5), init_time.substring(6, 8)]})
        });

        $('.accordion-check').change(function(){
            var obj = $(this);
            var parent = $(this).closest('.particular');
            var accordion = $('.accordion-div', parent);
            accordion.slideToggle(500, function(){
                return obj.is(':checked') ? "Collapse" : "Expand";
            });
        });

        $('.timepicker_wrap').css('width', '265px');

        $('.time-check').change(function(){
            var parent = $(this).closest('.row');
            if($(this).is(":checked")){
                $('.p-edit-oneline', parent).removeClass('light-text');
                $('.time-element', parent).prop('disabled', false);
            } else {
                $('.p-edit-oneline', parent).addClass('light-text');
                $('.time-element', parent).prop('disabled', true);
            }
        })
    });
    function onApply(){
        $('#post_form').submit();
    }
</script>
@endsection
