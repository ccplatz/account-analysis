@if (session()->has('success'))
    <div class="alert alert-success my-5">
        {{ session()->get('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger my-5">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
