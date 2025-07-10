<?php

namespace Triyatna\SmmLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array profile()
 * @method static array services()
 * @method static array order(int $serviceId, string $target, array $options = [])
 * @method static array status(string|array $orderIds)
 * @method static array refill(int $orderId)
 * @method static array refillStatus(int $refillId)
 *
 * @see \Triyatna\SmmLaravel\Smm
 */
class Smm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'smm';
    }
}
