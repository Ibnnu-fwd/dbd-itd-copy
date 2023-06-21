<ul>
    @foreach ($data['type'] as $type)
    <li class="xl:flex justify-between items-center">
        <span class="text-gray-600 dark:text-gray-400">{{ $type['name'] }}</span>
        <span class="text-gray-600 dark:text-gray-400">{{ $type['amount'] }}</span>
    </li>
    @endforeach
</ul>
