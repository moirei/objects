<?php

namespace MOIREI\Objects;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use RuntimeException;

abstract class BaseObject implements ArrayAccess, Arrayable
{
    protected $strict = true;
    protected array $extraProps = [];

    public function __construct(BaseObject|array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Make an instance of the object.
     *
     * @param BaseObject|array $attributes
     * @return static
     */
    public static function make(BaseObject|array $attributes): static
    {
        return new static($attributes);
    }

    /**
     * Fill the object with attributes.
     *
     * @param BaseObject|array $attributes
     * @return static
     */
    public function fill(BaseObject|array $attributes): static
    {
        if (!is_array($attributes)) {
            $attributes = $attributes->toArray();
        }

        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Check if the object the property as its own.
     * Does not include properties added dynamically.
     *
     * @param string $name
     * @return bool
     */
    public function hasOwnProperty(string $name): bool
    {
        $reflectionClass = new ReflectionClass($this);
        if (!$reflectionClass->hasProperty($name)) {
            return false;
        }
        $prop = $reflectionClass->getProperty($name);
        return $prop->isPublic() && !$prop->isStatic();
    }

    /**
     * Check if the object the property.
     *
     * @param string $name
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        return in_array($name, array_keys($this->extraProps)) || $this->hasOwnProperty($name);
    }

    /**
     * Set the value of key (property) on the object.
     * Throws RuntimeException if strict and property does not exist.
     *
     * @throws RuntimeException
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, mixed $value)
    {
        if ($this->strict) {
            if (!$this->hasOwnProperty($key)) {
                throw new RuntimeException("Cannot set property [$key]");
            }
            $this->$key = $value;
        } else {
            if ($this->hasOwnProperty($key)) {
                $this->$key = $value;
            } else {
                $this->extraProps[$key] = $value;
            }
        }
        return $value;
    }

    /**
     * Get the value of key (property) on the object.
     * Throws RuntimeException if strict and property does not exist.
     *
     * @throws RuntimeException
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null)
    {
        if ($this->strict && !$this->hasOwnProperty($key)) {
            throw new RuntimeException("Cannot access property [$key]");
        }

        return $this->$key ?? $this->extraProps[$key] ?? $default;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray()
    {
        $props = json_decode(json_encode($this), true);
        return array_merge(
            $this->extraProps,
            $props,
        );
    }

    /**
     * Clone the object.
     * @return static
     */
    public function clone(): static
    {
        return new static($this->toArray());
    }

    public function offsetExists(mixed $key): bool
    {
        return property_exists($this, $key) || isset($this->extraProps[$key]);
    }

    public function offsetGet(mixed $key): mixed
    {
        return $this->get($key);
    }

    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function offsetUnset(mixed $key): void
    {
        if ($this->offsetExists($key)) {
            unset($this->$key);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}
