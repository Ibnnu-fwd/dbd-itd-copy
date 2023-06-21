@props(['type' => 'submit', 'id' => '', 'class' => '', 'color' => '', 'type' => 'submit'])

<button id="{{ $id }}" type="{{$type}}"
    {!! $attributes->merge([
        'class' =>
            $class .
            ' inline-flex items-center px-6 py-2.5 border border-transparent rounded-md text-white bg-' .
            $color .
            '-800 hover:bg-' .
            $color .
            '-600 focus:bg-' .
            $color .
            '-600 active:bg-' .
            $color .
            '-900 focus:outline-none focus:ring-2 focus:ring-' .
            $color .
            '-500 focus:ring-offset-2 transition ease-in-out duration-150  text-xs 2xl:text-sm',
    ]) !!}>{{ $slot }}</button>
