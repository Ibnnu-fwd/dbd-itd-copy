@foreach ($longitude as $data)
    @foreach ($data['coordinate'] as $coordinate)
        <p>{{$coordinate['longitude']}}</p>
    @endforeach
@endforeach
