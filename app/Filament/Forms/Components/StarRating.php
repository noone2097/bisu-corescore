<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class StarRating extends Field
{
    protected string $view = 'filament.forms.components.star-rating';

    protected int $minValue = 1;
    protected int $maxValue = 5;

    public function min(int $value): static
    {
        $this->minValue = $value;

        return $this;
    }

    public function max(int $value): static
    {
        $this->maxValue = $value;

        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->default(null);
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): int
    {
        return $this->maxValue;
    }

    public function getState(): mixed
    {
        return parent::getState();
    }
}