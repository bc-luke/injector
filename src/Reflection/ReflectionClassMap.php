<?php

declare(strict_types=1);

namespace Bigcommerce\Injector\Reflection;

use ReflectionClass;
use SplObjectStorage;

class ReflectionClassMap
{
    /**
     * @var int
     */
    private $maxSize;

    /**
     * @var SplObjectStorage
     */
    private $map;

    public function __construct(int $maxSize)
    {
        $this->maxSize = $maxSize;
        $this->map = new SplObjectStorage();
    }

    public function put(ReflectionClass $reflection)
    {
        if ($this->map->count() >= $this->maxSize) {
            $this->evictOneObject();
        }
        $this->map[$reflection->getName()] = $reflection;
    }

    public function get(string $className): ?ReflectionClass
    {
        if (isset($this->map[$className])) {
            return $this->map[$className];
        }
        return null;
    }

    public function has(string $className): bool
    {
        return isset($this->map[$className]);
    }

    private function evictOneObject()
    {
        $this->map->rewind();
        if ($this->map->valid()) {
            $this->map->detach($this->map->current());
        }
    }
}
