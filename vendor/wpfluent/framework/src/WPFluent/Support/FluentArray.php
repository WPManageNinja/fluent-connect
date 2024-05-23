<?php

namespace FluentConnect\Framework\Support;

use ReflectionFunction;
use InvalidArgumentException;

class FluentArray extends Fluent
{
    protected $isFluent = false;

    public function __construct($items, $isFluent = false)
    {
        parent::__construct($items);

        $this->isFluent = $isFluent;
    }

    /**
     * Return the attributes
     * 
     * @return mixed
     */
    public function value()
    {
        return $this->toArray();
    }

    /**
     * Get value(s) from attributes
     * 
     * @param  string $key
     * @param  string $default
     * @return array
     */
    public function get($key = null, $default = null)
    {
        if (!$key) {
            return $this->toArray();
        }

        return $this->offsetGet($key) ?: parent::get($key, $default);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
    	$this->offsetSet($key, $value);
    }

	/**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return Arr::has($this->attributes, $offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ($offset === '*') return $this->attributes;

        return Arr::get($this->attributes, $offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        Arr::set($this->attributes, $offset, $value);
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
    	Arr::forget($this->attributes, $offset);
    }

    /**
     * Handle dynamic calls to the fluent instance to set attributes.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return $this
     */
    public function __call($method, $params)
    {
        $isUndef = true;

        if (method_exists(Arr::class, $method)) {
            $isUndef = false;
            $result = Arr::$method($this->attributes, ...$params);
        } elseif (function_exists($method)) {
            $isUndef = false;
            $result = $method(...$this->getParams($method, $params));
        } elseif (function_exists($func = Str::snake($method))) {
            $isUndef = false;
            $result = $func(...$this->getParams($func, $params));
        } elseif (function_exists($func = 'array_'.Str::snake($method))) {
            $isUndef = false;
            $result = $func(...$this->getParams($func, $params));
        }

        if ($isUndef) {
            throw new InvalidArgumentException(
                "Undefined method ".__CLASS__."::{$method}"
            );
        }

        if ($this->isFluent) {
            return is_scalar($result) ? $result : new static(
                $result, $this->isFluent
            );
        }

        return $result;
    }

    /**
     * Retuens the string representation of the array
     * 
     * @return string [description]
     */
    public function __toString()
    {	
        return json_encode($this->attributes, JSON_PRETTY_PRINT);
    }

    /**
     * Prepare the function parameters in the right order.
     * The attributes should be in the place of haystack,
     * if no haystack exists then put it at first place.
     * 
     * @param  String $func
     * @param  array $params
     * @return array
     */
    public function getParams($func, $params)
    {
        if (!$params) return [$this->attributes];

        $reflection = new ReflectionFunction($func);

        $haystack = array_filter($reflection->getParameters(), function($i) {
            return $i->getName() === 'haystack';
        });

        if (!$haystack) {
            array_unshift($params, $this->attributes);
            return $params;
        }

        foreach ($reflection->getParameters() as $key => $parameter) {
            if ($parameter->getName() === 'haystack') {
                $params = Arr::insertAt($params, $key, $this->attributes);
            }
        }
        
        return $params;
    }

    /**
     * Dump the attributes.
     * 
     * @return array
     */
    public function dd()
    {
        $attributes = $this->attributes;

        if ($keys = func_get_args()) {
            $attributes = $this->only(...$keys);
        }

        print_r($attributes); die;
    }
}
