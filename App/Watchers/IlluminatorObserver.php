<?php
declare(strict_types=1);

namespace App\Domains\Illuminator\Observers;


use App\Domains\Illuminator\Illuminator;

class IlluminatorObserver
{
    public function saved(Illuminator $illuminator)
    {
        if (!$illuminator
            ->accountManagers()
            ->where('account_manager_id', $illuminator->primaryAccountManagerId)
            ->exists()) {

            $illuminator->accountManagers()->attach($illuminator->primaryAccountManagerId);
        }
    }
}
