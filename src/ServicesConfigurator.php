<?php

declare(strict_types=1);

namespace Bigcommerce\Injector;

class ServicesConfigurator
{
    public function set(string $id)
    {
        return new ServiceConfigurator();
    }
}
