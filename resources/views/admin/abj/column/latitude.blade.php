@foreach ($latitude as $data)
    @foreach ($data['coordinate'] as $coordinate)
        <p>{{$coordinate['latitude']}}</p>
    @endforeach
@endforeach
