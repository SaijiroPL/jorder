<div class="row mt-4 mb-4">
    <div class="col-12 mb-3">
        <div class="d-inline-block text-white font-bold border-blue ">
            <a class="text-white d-inline-block bg-blue2 border-rightBlue p-3 w-60px" href="{{ route('admin.dish') }}" >DISH</a>
            <a class="text-white p-3 d-inline-block border-rightBlue w-60px" href="{{ route('admin.catgory') }}">CATEGORY</a>
            <a class="text-white p-3 d-inline-block border- w-60px border-rightBlue" href="{{ route('admin.option') }}">OPTION</a>
            <a class="text-white p-3 d-inline-block border-rightBlue  w-60px" href="#">DISCOUNT</a>
        </div>
        <a href="{{ route('admin.dish.add') }}" class="text-white  btnCreateNewDiscount">
            CREATE NEW DISH
            <img src="{{ asset('img/Group728white.png') }}" height="15" />
        </a>
    </div>
</div>
