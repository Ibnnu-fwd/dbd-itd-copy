@props(['route' => '#', 'id' => '#', 'color' => 'gray', 'icon' => '', 'onclick' => '', 'class' => ''])

<a id="{{ $id }}" type="button" onclick="{{ $onclick }}" href="{{ $route }}" {!! $attributes->merge([
    'class' =>
        $class .
        ' text-white bg-' .
        $color .
        '-800 hover:bg-' .
        $color .
        '-800 focus:ring-4 focus:outline-none focus:ring-' .
        $color .
        '-300 font-medium rounded-lg text-xs 2xl:text-sm p-2.5 text-center inline-flex items-center dark:bg-' .
        $color .
        '-600 dark:hover:bg-' .
        $color .
        '-700 dark:focus:ring-' .
        $color .
        '-800',
]) !!}>
    <i class="{{ $icon }}"></i>
</a>
