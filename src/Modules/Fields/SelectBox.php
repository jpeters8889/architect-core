<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Illuminate\Database\Eloquent\Model;

class SelectBox extends AbstractField
{
    protected array $options;

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function metaData(): array
    {
        return ['options' => $this->formatOptions()];
    }

    protected function booted(): void
    {
        $this->getValueForTableUsing(function (Model $model) {
            $value = $model->{$this->column};

            return collect($this->formatOptions())
                ->filter(fn (array $option) => $option['key'] === $value)
                ->take(1)
                ->values()
                ->first()['value'];
        });
    }

    protected function formatOptions(): array
    {
        if (count($this->options) > 0 && is_array($this->options[0])) {
            return $this->options;
        }

        return array_map(fn ($option) => ['key' => $option, 'value' => $option], $this->options);
    }
}
