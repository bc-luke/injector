<?php

declare(strict_types=1);

namespace Bigcommerce\Injector\Cache;

class InjectorReflectionCache
{
    private $methodExistence;

    private $methodPublicVisibility;

    private $methodSignatures;

    public function __construct()
    {
        $this->methodExistence = [];
        $this->methodPublicVisibility = [];
        $this->methodSignatures = [];
    }

    public function methodExistenceCount(): int
    {
        return count($this->methodExistence);
    }

    public function methodPublicVisibilityCount(): int
    {
        return count($this->methodPublicVisibility);
    }

    public function methodSignaturesCount(): int
    {
        return count($this->methodSignatures);
    }


    public function classHasMethod(string $class, string $method, callable $resolver): bool
    {
        $reference = $this->getReference($class, $method);
        if (!array_key_exists($reference, $this->methodExistence)) {
            return $this->methodExistence[$reference] = $resolver();
        }

        return $this->methodExistence[$this->getReference($class, $method)];
    }

    public function methodIsPublic(string $class, string $method, callable $resolver): bool
    {
        $reference = $this->getReference($class, $method);
        if (!array_key_exists($reference, $this->methodPublicVisibility)) {
            $this->methodPublicVisibility[$reference] = $resolver();
        }

        return $this->methodPublicVisibility[$reference];
    }

    public function getMethodSignature(string $class, string $method, callable $resolver): array
    {
        $reference = $this->getReference($class, $method);
        if (!array_key_exists($reference, $this->methodSignatures)) {
            $this->methodSignatures[$reference] = $resolver();
        }

        return $this->methodSignatures[$reference];
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
