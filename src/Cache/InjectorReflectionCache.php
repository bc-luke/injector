<?php

declare(strict_types=1);

namespace Bigcommerce\Injector\Cache;

class InjectorReflectionCache
{
    private $classMethods = [];

    private $methodExistence;

    private $methodVisibility;

    private $methodSignatures;

    public function __construct()
    {
        $this->methodExistence = [];
        $this->methodVisibility = [];
        $this->methodSignatures = [];
    }


    public function visitNonExistentMethod(string $class, string $method)
    {
        $reference = $this->getReference($class, $method);
        $this->methodExistence[$reference] = false;
    }

    public function visitNonPublicMethod(string $class, string $method)
    {
        $reference = $this->getReference($class, $method);
        $this->methodExistence[$reference] = true;
        $this->methodVisibility[$reference] = 'non_public';
    }

    public function visitPublicMethod(string $class, string $method, array $signature)
    {
        $reference = $this->getReference($class, $method);
        $this->methodExistence[$reference] = true;
        $this->methodVisibility[$reference] = 'public';
        $this->methodSignatures[$reference] = $signature;
    }

    // do we need this?
    public function get(string $class, string $method): ?array
    {
        $key = $this->getReference($class, $method);
        return $this->classMethods[$key] ?? null;
    }

    /**
     * @param string $class
     * @param string $method
     * @return string
     */
    private function getReference(string $class, string $method): string
    {
        return "$class::$method";
    }
}
