@props(['id' => '', 'name' => '', 'label' => '', 'required' => false, 'value' => ''])

<div class="mb-4">
    <label for="" class="text-xs 2xl:text-sm mb-2 block">
        {{ $label }} {!! $required ? '<span class="text-red-500">*</span>' : '' !!}
    </label>
    <div class="flex items-center justify-center w-full">
        <label for="{{ $id }}"
            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 fw-sm mb-3"></i>
                <p class="mb-2 text-xs 2xl:text-sm text-center text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">
                        Klik disini
                    </span>
                    atau seret file untuk mengunggah
                </p>
                <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400">
                    PNG or JPG (MAX. 2 MB)
                </p>
            </div>
            <input id="{{ $id }}" type="file" class="hidden" name="{{ $name }}"
                {{ $required ? 'required' : '' }} />
        </label>
    </div>

    @error($name)
        <p class="text-xs 2xl:text-sm text-red-500 mt-3">{{ $message }}</p>
    @enderror
</div>
