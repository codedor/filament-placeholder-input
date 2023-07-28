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
            navigator.clipboard.writeText(key)

            new FilamentNotification()
                .title('Copied \'' + key + '\' to clipboard')
                .success()
                .send()
        }
    }">
        @if ($linksWith && $linksWith->count() > 1)
            <select x-model="linked">
                @foreach ($linksWith as $target => $label)
                    <option value="{{ $target }}">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        @endif

        <table>
            @foreach ($variables as $variable)
                <tr class="flex gap-4 items-center">
                    @if ($linksWith && $linksWith->isNotEmpty())
                        <td x-on:click="addToBody($event, @js($variable->getKey()))">
                            <x-heroicon-o-plus class="w-5 h-5" />
                        </td>
                    @endif

                    @if ($canCopy)
                        <td x-on:click="copyToClipboard(@js($variable->getKey()))">
                            <x-heroicon-o-clipboard class="w-5 h-5" />
                        </td>
                    @endif

                    <td>
                        {{ $variable->getLabel() }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-dynamic-component>
