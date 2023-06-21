@props(['id' => '', 'name' => '', 'label' => '', 'isFit' => 'fit', 'required' => false])

<div class="form-control w-full {{ $isFit == 'fit' ? 'max-w-xs' : '' }}">
    <label class="label pt-0 pl-0">
        <span class="text-xs 2xl:text-sm font-medium">{{ $label }} {!! $required == true ? '<sup class="text-error">*</sup>' : '' !!}</span>
    </label>
    <select name="{{ $name }}" id="{{ $id }}" {!! $attributes->merge([
        'class' => 'select select-bordered',
    ]) !!}>
        {{ $slot }}
    </select>
    <label class="label">
        @error($name)
            <span class="label-text-alt text-error">{{ $message }}</span>
        @enderror
    </label>
</div>
