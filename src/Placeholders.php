<?php

namespace Codedor\FilamentPlaceholderInput;

use Illuminate\Support\Collection;

class Placeholders
{
    public static function parse(?string $content, array|Collection $variables)
    {
        $data = Collection::wrap($variables)
            ->mapWithKeys(fn (PlaceholderVariable $value) => [$value->getKey() => $value->getValue()])
            ->toArray();

        return preg_replace_callback(
            '/{{ (?<keyword>.*?) }}/',
            fn ($match) => data_get($data, $match['keyword']),
            $content ?? ''
        );
    }
}
