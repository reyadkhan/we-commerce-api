<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CREATED()
 * @method static static APPROVED()
 * @method static static REJECTED()
 * @method static static PROCESSING()
 * @method static static SHIPPED()
 * @method static static DELIVERED()
 */
final class OrderStatus extends BasicStatus
{
    public static function getUpdateAbleValues(): array {
        $allValues = static::getValues();
        unset($allValues[array_search(static::CREATED, $allValues)]);
        return $allValues;
    }
}
