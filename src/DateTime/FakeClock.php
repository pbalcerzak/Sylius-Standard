<?php

declare(strict_types=1);

namespace App\DateTime;

class FakeClock implements ClockInterface
{

    public function isNight(): bool
    {
        return true;
    }
}
