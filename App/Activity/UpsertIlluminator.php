<?php

declare(strict_types=1);

namespace App\Domains\Illuminator\Actions;

use App\Domains\Advertiser\AdvertiserStatus;
use App\Domains\Agreement\Actions\CreateAgreement;
use App\Domains\Auth\User;
use App\Domains\Core\Address;
use App\Domains\Illuminator\Illuminator;

class UpsertIlluminator
{
    public function execute(
        Illuminator $illuminator,
        string $name,
        ?string $website,
        ?string $webhookUrl,
        Address $contactAddress,
        Address $billingAddress,
        Address $technicalAddress,
        User $primaryAccountManager,
        string $defaultCostModel,
        float $defaultCostValue
    ): Illuminator {
        if (!$contactAddress->exists) {
            $contactAddress->save();
        }
        if (!$billingAddress->exists && $contactAddress->isSame($billingAddress)) {
            $billingAddress = $contactAddress;
        } else {
            $billingAddress->save();
        }
        if (!$technicalAddress->exists && $contactAddress->isSame($technicalAddress)) {
            $technicalAddress = $contactAddress;
        } else {
            $technicalAddress->save();
        }

        $illuminator->forceFill(
            compact('name', 'website', 'webhookUrl', 'defaultCostModel', 'defaultCostValue') +
            [
                'status' => AdvertiserStatus::PENDING_AGREEMENT,
            ]
        );
        $illuminator->contactAddress()->associate($contactAddress);
        $illuminator->billingAddress()->associate($billingAddress);
        $illuminator->technicalAddress()->associate($technicalAddress);
        $illuminator->primaryAccountManager()->associate($primaryAccountManager);
        $illuminator->save();
        if (!$illuminator->agreementId) {
            $agreement = app(CreateAgreement::class)->execute(
                $primaryAccountManager,
                $illuminator,
                config('agreement.illuminator')
            );
            $illuminator->agreementId = $agreement->getKey();
            $illuminator->save();
        }

        return $illuminator;
    }
}
