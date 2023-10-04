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
        linked: @js($linksWith->keys()->first()),
        addToBody (e, key) {
            // Append the variable (key) to the body
            let original = $wire.get('data.' + this.linked)
            let updated = ((! original) ? '' : original + ' ') + '@{{ ' + key + ' }}'

            $wire.set('data.' + this.linked, updated)

            // Let tiptap know the content has been updated, on the next tick
            window.setTimeout(() => $dispatch('refresh-tiptap-editors'), 0)
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
                <x-filament::input.wrapper>
                    <x-filament::input.select x-model="linked">
                        @foreach ($linksWith as $target => $label)
                            <option value="{{ $target }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        @endif

        <table>
            @foreach ($variables as $variable)
                <tr class="flex items-center" >
                    @if ($linksWith && $linksWith->isNotEmpty())
                        <td class="mb-1" x-on:click="addToBody($event, @js($variable->getKey()))">
                            <x-filament::icon-button
                                icon="heroicon-o-plus"
                                type="button"
                                color="gray"
                                size="sm"
                                class="cursor-pointer hover:bg-gray-100"
                            />
                        </td>
                    @endif
                    @if ($canCopy)
                        <td class="mb-1" x-on:click="copyToClipboard(@js($variable->getKey()))">
                            <x-filament::icon-button
                                icon="heroicon-o-clipboard"
                                type="button"
                                color="gray"
                                size="sm"
                                class="cursor-pointer hover:bg-gray-100"
                            />
                        </td>
                    @endif
                    <td class="mb-1">
                        <p class="fi-ta-text-item text-base text-gray-950 dark:text-white ps-1">
                            {{ $variable->getLabel() }}
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-dynamic-component>
