<?php

namespace Codedor\FilamentPlaceholderInput;

class PlaceholderVariable
{
    public function __construct(
        public ?string $key = null,
        public ?string $label = null,
        public mixed $value = null,
    ) {
        //
    }

    public static function make(
        string $key = null,
        string $label = null,
        mixed $value = null,
    ): static {
        return new static($key, $label, $value);
    }

    public function key(?string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function label(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getKey(): ?string
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
