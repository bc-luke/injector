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

    public function countMethodExistenceEntries(): int
    {
        return count($this->methodExistence);
    }

    public function countMethodPublicVisibilityEntries(): int
    {
        return count($this->methodPublicVisibility);
    }

    public function countMethodSignatureEntries(): int
    {
        return count($this->methodSignatures);
    }

    public function getMethodExistence(): array
    {
        return $this->methodExistence;
    }

    public function getMethodPublicVisibility(): array
    {
        return $this->methodPublicVisibility;
    }

    public function getMethodSignatures(): array
    {
        return $this->methodSignatures;
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
