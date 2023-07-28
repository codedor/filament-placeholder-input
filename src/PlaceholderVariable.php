<?php

namespace Codedor\FilamentPlaceholderInput;

class PlaceholderVariable
{
    public function __construct(
        public null | string $key = null,
        public null | string $label = null,
        public mixed $value = null,
    ) {
        //
    }

    public static function make(
        null | string $key = null,
        null | string $label = null,
        mixed $value = null,
    ): static {
        return new static($key, $label, $value);
    }

    public function key(null | string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function label(null | string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getKey(): null | string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label ?? $this->getKey();
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
        ];
    }
}
