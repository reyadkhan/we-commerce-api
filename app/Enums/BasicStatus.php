<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

abstract class BasicStatus extends Enum
{
    const CREATED = "Created";

    const APPROVED =   "Approved";

    const REJECTED =   "Rejected";

    const PROCESSING = "Processing";

    const SHIPPED = "Shipped";

    const DELIVERED = "Delivered";
}
