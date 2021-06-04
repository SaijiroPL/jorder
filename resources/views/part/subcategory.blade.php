@foreach ($subs as $sub)
    <option class="option-subcat" data-parent={{ $sub->parent->id }} value="{{ $sub->id }}">{{ $sub->name_en }}</option>
@endforeach
