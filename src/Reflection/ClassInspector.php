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

    public function __construct(
        InjectorReflectionCache $reflectionCache,
        ReflectionClassMap $reflectionClassMap,
        ParameterInspector $parameterInspector
    ) {
        $this->reflectionCache = $reflectionCache;
        $this->reflectionClassMap = $reflectionClassMap;
        $this->parameterInspector = $parameterInspector;
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
            $this->reflectionClassMap->put($reflectionClass);
        }

        return $reflectionClass;
    }
}
