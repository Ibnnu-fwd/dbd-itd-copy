{{-- <span>
    ((@foreach ($data['abj'] as $abj)
        {{ $abj['abj_total'] }} {{ $loop->last ? '' : '+' }}
    @endforeach)
    / {{ $data['abj']->count() }}) * 100
</span>
<p>
    = {{ $data['abj_total'] }} % (percent)
</p> --}}
<span>{{ $data['abj_total'] }}%</span>
