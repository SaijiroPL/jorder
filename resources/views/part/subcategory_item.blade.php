<li class="ui-state-default btn white pt-2 radius pb-2 mb-3 pl-2 w-95 pr-0  waves-effect waves-light subcat" onclick="onSub(this)"
    data-id="{{ $sub->id }}"
    data-parent="{{ $sub->parent_id }}"
    data-english="{{ $sub->name_en }}"
    data-chinese="{{ $sub->name_cn }}"
    data-japanese="{{ $sub->name_jp }}"
    id="{{ $sub->id }}">
    <h6 class="font-weight-bold black-text mb-0 text-left cat-caption">
        <span class="fa fa-navicon" style="margin:-12px 9px 0 9px;"></span>
        <span class="fs-15" id="s_ti_{{ $sub->id }}">
            @if(strlen($sub->name_en) > 11)
                {{ substr($sub->name_en, 0, 11)."..." }}
            @else
                {{ $sub->name_en }}
            @endif
        </span>
    </h6>
</li>
