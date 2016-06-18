@if(session('msg'))
    <div class="alert alert-{{ session('msg')['type'] }}" role="alert">
        {!! session('msg')['msg'] !!}
    </div>
@endif
