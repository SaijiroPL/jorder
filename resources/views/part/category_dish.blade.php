@foreach ($dishes as $ds)
    <li class="btn white pt-2 radius pb-2 mb-3 pl-2 pr-2 w-95 waves-effect waves-light category-dish"
        data-dish="{{ $ds->id }}"
        data-category = "{{$ds->categories_id}}"
        onclick="onDish(this)"
        id="{{ $ds->id }}">
        <h6 class="font-weight-bold black-text mb-0 text-left" style="white-space:nowrap;overflow:hidden">
            <span class="fa fa-navicon" style="margin:-12px 9px 0 9px;"></span>
            <span class="fs-15">
            @if(strlen($ds->name_en) > 22)
                    {{ substr($ds->name_en, 0, 22)."..." }}
                @else
                    {{ $ds->name_en }}
                @endif
        </span>
        </h6>
    </li>
@endforeach
<input type="hidden" id="dish_ids" value="{{$dish_ids}}">
<input type="hidden" id="dish_count" value="{{$dish_count}}">
