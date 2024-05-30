<?php

declare(strict_types=1);

namespace Bigcommerce\Injector\Reflection;

use Bigcommerce\Injector\Cache\InjectorReflectionCache;
use ReflectionClass;

class ClassInspector
{
    /**
     * @var InjectorReflectionCache
     */
    private $reflectionCache;

    /**
     * @var ReflectionClassMap
     */
    private $reflectionClassMap;

    /**
     * @var ParameterInspector
     */
    private $parameterInspector;
    public static $reflectionClassesCreated = 0;

    public function __construct(
        InjectorReflectionCache $reflectionCache,
        ReflectionClassMap $reflectionClassMap,
        ParameterInspector $parameterInspector
    ) {
        $this->reflectionCache = $reflectionCache;
        $this->reflectionClassMap = $reflectionClassMap;
        $this->parameterInspector = $parameterInspector;
    }

    public function inspectMethod(string $class, string $method): void
    {
        if ($this->classHasMethod($class, $method)) {
            if ($this->methodIsPublic($class, $method)) {
                $this->getMethodSignature($class, $method);
            }
        }
    }

    public function classHasMethod(string $class, string $method): bool
    {
        return $this->reflectionCache->classHasMethod($class, $method, function () use ($class, $method) {
            return $this->getReflectionClass($class)->hasMethod($method);
        });
    }


    public function methodIsPublic(string $class, string $method): bool
    {
        return $this->reflectionCache->methodIsPublic($class, $method, function () use ($class, $method) {
            return $this->getReflectionClass($class)->getMethod($method)->isPublic();
        });
    }

    public function getMethodSignature(string $class, string $method): array
    {
        return $this->reflectionCache->getMethodSignature($class, $method, function () use ($class, $method) {
            $reflectionClass = $this->getReflectionClass($class);

            return $this->parameterInspector->getSignatureByReflectionClass($reflectionClass, $method);
        });
    }

    private function getReflectionClass(string $class): ReflectionClass
    {
        if ($this->reflectionClassMap->has($class)) {
            $reflectionClass = $this->reflectionClassMap->get($class);
        } else {
            $reflectionClass = new ReflectionClass($class);
            self::$reflectionClassesCreated++;
            $this->reflectionClassMap->put($reflectionClass);
        }

        return $reflectionClass;
    }

    public function methodExistenceCount(): int
    {
        return $this->reflectionCache->methodSignaturesCount();
    }

    public function methodPublicVisibilityCount(): int
    {
        return $this->reflectionCache->methodPublicVisibilityCount();
    }

    public function methodSignaturesCount(): int
    {
        return $this->reflectionCache->methodSignaturesCount();
    }
}
