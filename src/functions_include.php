<?php

declare(strict_types=1);

// Don't redefine the functions if included multiple times.
if (!\function_exists('App\Bundle\SwooleBundle\Functions\replace_object_property')) {
    require __DIR__.'/functions.php';
}

if (!function_exists('swoole_cpu_num')) {
    function swoole_cpu_num(): int
    {
        return \OpenSwoole\Util::getCPUNum();
    }
}
