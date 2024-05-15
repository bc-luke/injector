<?php

declare(strict_types=1);

namespace Bigcommerce\Injector\Cache;

interface BulkReadableServiceCacheInterface
{
    public function getAll(): array;
}
