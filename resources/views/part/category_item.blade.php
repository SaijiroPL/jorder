<li class="btn white pt-2 radius pb-2 mb-3 pl-2 w-95 pr-0  waves-effect waves-light cat-button"
    onclick="onMain(this)"
    data-id="{{ $cat->id }}"
    data-hassubs="{{ $cat->has_subs }}"
    data-english="{{ $cat->name_en }}"
    data-chinese="{{ $cat->name_cn }}"
    data-japanese="{{ $cat->name_jp }}"
    data-isunlimited="{{ $cat->is_unlimited }}"
    id="{{ $cat->id }}">
    <h6 class="font-weight-bold black-text mb-0 text-left cat-caption">
        <span class="fa fa-navicon" style="margin:-12px 9px 0 9px;"></span>
        <span class="fs-15" id="c_ti_{{ $cat->id }}">
            @if(strlen($cat->name_en) > 15)
                {{ substr($cat->name_en, 0, 15)."..." }}
            @else
                {{ $cat->name_en }}
            @endif
        </span>
    </h6>
</li>


