<?php

declare(strict_types=1);

namespace App\Domains\Illuminator;

use App\Core\Enum;

class IlluminatorStatus extends Enum
{
    public const PENDING_AGREEMENT = 'pending-agreement';

    public const PENDING_SIGNATURE = 'pending-signature';

    public const ACTIVE = 'active';

    public const OUTDATED = 'outdated';

    public static function values(): array
    {
        return [
            self::PENDING_AGREEMENT,
            self::PENDING_SIGNATURE,
            self::ACTIVE,
        ];
    }
}
