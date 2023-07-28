<?php

namespace Codedor\FilamentPlaceholderInput\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Arr;

class PlaceholderInput extends Field
{
    protected string $view = 'filament-placeholder-input::forms.components.placeholder-input';

    public null | array | string | Closure $linksWith = null;

    public array | Closure $variables = [];

    public array | Closure $labels = [];

    public bool | Closure $canCopy = false;

    public function linksWith(null | array | string | Closure $linksWith): static
    {
        if (is_string($linksWith)) {
            $linksWith = Arr::wrap($linksWith);
        }

        if (Arr::isList($linksWith)) {
            $linksWith = array_combine($linksWith, $linksWith);
        }

        $this->linksWith = $linksWith;

        return $this;
    }

    public function getLinksWith(): array
    {
        return $this->evaluate($this->linksWith);
    }

    public function variables(array | Closure $variables): static
    {
        $this->variables = $variables;

        return $this;
    }

    public function getVariables(): array
    {
        return $this->evaluate($this->variables);
    }

    public function labels(array | Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function getLabels(): array
    {
        return $this->evaluate($this->labels);
    }

    public function canCopy(bool | Closure $canCopy = true): static
    {
        $this->canCopy = $canCopy;

        return $this;
    }

    public function getCanCopy(): bool
    {
        return $this->evaluate($this->canCopy);
    }
}
