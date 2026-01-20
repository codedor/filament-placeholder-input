@php
    $linksWith = $getLinksWith();
    $variables = $getVariables();
    $canCopy = $canCopy();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        linked: @js($getDefaultLink()),
        getTiptap (linked) {
            let editors = document.querySelectorAll('.fi-input-wrp-content-ctn > div');

            if (editors.length === 0) {
                return null;
            }

            return [...editors].filter(function (editor) {
                return editor._x_dataStack && editor._x_dataStack[1].$statePath === 'data.' + linked;
            });
        },
        addToBody (e, key) {
            let tiptap = this.getTiptap(this.linked)

            if (tiptap.length) {
                tiptap[0]._x_dataStack[0].getEditor().chain().focus().insertContent('@{{ ' + key + ' }}').run()
                return
            }

            // Append the variable (key) to the body
            let original = $wire.get('data.' + this.linked)
            let updated = ((! original) ? '' : original + ' ') + '@{{ ' + key + ' }}'

            $wire.set('data.' + this.linked, updated)
        },
        copyToClipboard (key) {
            // Copy the variable (key) to the clipboard
            // Only works on secure origins (https://)
            navigator.clipboard.writeText('@{{ ' + key + ' }}')

            new FilamentNotification()
                .title('Copied \'' + key + '\' to clipboard')
                .success()
                .send()
        }
    }">
        @if ($linksWith && $linksWith->count() > 1)
            <div class="w-full flex gap-1 mb-4">
                <x-filament::input.wrapper class="w-full">
                    <x-filament::input.select class="w-full" x-model="linked">
                        @foreach ($linksWith as $target => $label)
                            <option value="{{ $target }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        @endif

        <div class="w-full flex flex-col gap-2">
            @foreach ($variables as $variable)
                <div class="flex gap-2 items-center">
                    @if ($linksWith && $linksWith->isNotEmpty())
                        <div x-on:click="addToBody($event, @js($variable->getKey()))">
                            <x-filament::icon-button
                                icon="heroicon-o-plus"
                                type="button"
                                color="gray"
                                size="sm"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            />
                        </div>
                    @endif

                    @if ($canCopy)
                        <div x-on:click="copyToClipboard(@js($variable->getKey()))">
                            <x-filament::icon-button
                                icon="heroicon-o-clipboard"
                                type="button"
                                color="gray"
                                size="sm"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            />
                        </div>
                    @endif

                    <p class="fi-ta-text-item text-base text-gray-950 dark:text-white ps-1">
                        {{ $variable->getLabel() }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</x-dynamic-component>
