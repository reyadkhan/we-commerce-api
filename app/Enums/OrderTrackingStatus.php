<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static APPROVED()
 * @method static static REJECTED()
 * @method static static PROCESSING()
 * @method static static SHIPPED()
 * @method static static DELIVERED()
 * @method static static UPDATED()
 * @method static static REMOVED()
 */
final class OrderTrackingStatus extends BasicStatus
{
    const UPDATED =  "Updated";

    const REMOVED = "Removed";
}
