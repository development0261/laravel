<?php

declare(strict_types=1);

namespace App\Domains\Illuminator;

use App\Domains\AgreementStatus;
use App\Domains\Auth\Tokenable;
use App\Domains\Auth\User;

class IlluminatorPolicy
{
    public function create(Tokenable $user)
    {
        return $user->hasAccessTo('create:illuminator') && $user instanceof User && $user->isActive();
    }

    public function read(Tokenable $user, Illuminator $illuminator)
    {
        return $this->hasAccess('read:illuminator', $user, $illuminator);
    }

    public function update(Tokenable $user, Illuminator $illuminator)
    {
        return $this->hasAccess('update:illuminator', $user, $illuminator);
    }

    public function acceptAgreement(Tokenable $user, Illuminator $illuminator)
    {
        return $user instanceof User && !$user->isAdmin() && $illuminator->agreement->status === AgreementStatus::PENDING;
    }

    public function approveAgreement(Tokenable $user, Illuminator $illuminator)
    {
        return $user instanceof User && $user->isAdmin() && in_array($illuminator->agreement->status, [
                AgreementStatus::ACCEPTED,
                AgreementStatus::FOR_APPROVAL,
            ], true);
    }

    private function hasAccess(string $ability, Tokenable $user, Illuminator $illuminator)
    {
        return $user->hasAccessTo($ability) && (
            ($user instanceof User && ($user->isAdmin() || $illuminator->isManagedBy($user)))
                || ($user instanceof Illuminator && $user->getKey() === $illuminator->getKey())
        );
    }
}
