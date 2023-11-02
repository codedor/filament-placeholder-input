<?php

namespace Codedor\FilamentPlaceholderInput\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class PlaceholderInput extends Field
{
    protected string $view = 'filament-placeholder-input::forms.components.placeholder-input';

    public null|array|string|Closure $linksWith = null;

    public null|string|Closure $defaultLink = null;

    public null|array|Closure $variables = null;

    public bool|Closure $canCopy = false;

    public function linksWith(null|array|string|Closure $linksWith): static
    {
        if (is_string($linksWith)) {
            $linksWith = Arr::wrap($linksWith);
        }

        $this->linksWith = $linksWith;

        return $this;
    }

    public function getLinksWith(): Collection
    {
        $form = $this->getLivewire()->getForm('form');

        return collect($this->evaluate($this->linksWith))->mapWithKeys(fn ($key) => [
            $key => $form->getComponent("data.{$key}")->getLabel(),
        ]);
    }

    public function variables(array|Closure $variables): static
    {
        $this->variables = $variables;

        return $this;
    }

    public function getVariables(): ?array
    {
        return $this->evaluate($this->variables) ?? method_exists($this->getRecord(), 'getPlaceholderVariables')
            ? $this->getRecord()->getPlaceholderVariables()
            : [];
    }

    public function defaultLink(string|Closure $defaultLink): static
    {
        $this->defaultLink = $defaultLink;

        return $this;
    }

    public function getDefaultLink(): string
    {
        return $this->evaluate($this->defaultLink)
            ?? $this->getLinksWith()->keys()->first();
    }

    public function copyable(bool|Closure $canCopy = true): static
    {
        $this->canCopy = $canCopy;

        return $this;
    }

    public function canCopy(): bool
    {
        return Request::secure() && $this->evaluate($this->canCopy);
    }
}
