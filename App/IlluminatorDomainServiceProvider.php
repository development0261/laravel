<?php

declare(strict_types=1);

namespace App\Domains\Illuminator;

use App\Domains\DomainServiceProvider;
use App\Domains\Illuminator\Observers\IlluminatorObserver;

class IlluminatorDomainServiceProvider extends DomainServiceProvider
{
    public function morphMap(): array
    {
        return [
            'illuminator' => Illuminator::class,
        ];
    }

    public function policies(): array
    {
        return [
            Illuminator::class => IlluminatorPolicy::class,
        ];
    }

    public function observers(): array
    {
        return [
            Illuminator::class => IlluminatorObserver::class,
        ];
    }
}
