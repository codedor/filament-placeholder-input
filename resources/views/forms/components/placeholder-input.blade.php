@php
    $linksWith = $getLinksWith();
    $variableLabels = $getLabels();
    $canCopy = $getCanCopy() && Request::secure();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        linked: @js(array_keys($linksWith)[0] ?? null),
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
        @if ($linksWith && count($linksWith) > 1)
            <select x-model="linked">
                @foreach ($linksWith as $target => $label)
                    <option value="{{ $target }}">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        @endif

        <table>
            @foreach ($getVariables() as $key)
                <tr class="flex gap-4 items-center">
                    @if ($linksWith)
                        <td x-on:click="addToBody($event, @js($key))">
                            <x-heroicon-o-plus class="w-5 h-5" />
                        </td>
                    @endif

                    @if ($canCopy)
                        <td x-on:click="copyToClipboard(@js($key))">
                            <x-heroicon-o-clipboard class="w-5 h-5" />
                        </td>
                    @endif

                    <td>
                        {{ $variableLabels[$key] ?? $key }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-dynamic-component>
