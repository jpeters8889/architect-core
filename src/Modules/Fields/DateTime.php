<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DateTime extends AbstractField
{
    protected string $format = 'Y-m-d H:i:s';

    protected function booted(): void
    {
        $this->getValueForTableUsing(function (Model $model) {
            $value = $model->{$this->column};

            if (! $value instanceof Carbon) {
                $value = Carbon::make($value);
            }

            return $value?->format($this->format);
        });
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }
}
