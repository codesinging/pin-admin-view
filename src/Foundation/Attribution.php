<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Foundation;

use Illuminate\Support\Str;

class Attribution extends Buildable
{
    /**
     * The attribution's prefix for property.
     */
    const PROPERTY_PREFIX = ':';

    /**
     * The attribution name.
     * @var string
     */
    protected $name;

    /**
     * The attribution value.
     * @var mixed
     */
    protected $value;

    /**
     * If the attribution is a property.
     * @var bool
     */
    protected $isProperty = false;

    /**
     * Attribution constructor.
     *
     * @param string $name
     * @param null|mixed $value
     * @param bool $isProperty
     */
    public function __construct(string $name, $value = null, bool $isProperty = false)
    {
        $this->parse($name, $value, $isProperty);
    }

    /**
     * @param string $name
     * @param null|mixed $value
     * @param bool $isProperty
     */
    protected function parse(string $name, $value = null, bool $isProperty = false): void
    {
        $this->isProperty = $isProperty;

        if (Str::startsWith($name, self::PROPERTY_PREFIX)){
            $this->name = substr($name, 1);
            $this->isProperty = true;
        } else {
            $this->name = $name;
        }

        if (is_string($value)){
            if (Str::startsWith($value, self::PROPERTY_PREFIX)){
                $this->value = substr($value, 1);
                $this->isProperty = true;
            } elseif (Str::startsWith($value, ['\:', '\\'])){
                $this->value = substr($value, 1);
            } else {
                $this->value = $value;
            }
        } elseif ($value === true){
            $this->value = 'true';
            $this->isProperty = true;
        } elseif ($value === false){
            $this->value = 'false';
            $this->isProperty = true;
        } elseif (is_int($value) || is_float($value) || is_double($value)){
            $this->value = (string)$value;
            $this->isProperty = true;
        } elseif (is_null($value) && $this->isProperty === true){
            $this->value = 'true';
        } elseif (is_array($value)){
            $this->value = json_encode($value);
            $this->isProperty = true;
        }
    }

    /**
     * Get the attribute name without colon.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the property value.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Whether the attribution is a `v-bind` property.
     *
     * @return bool
     */
    public function isProperty(): bool
    {
        return $this->isProperty;
    }

    /**
     * Determine if an attribution is the same with this attribution.
     *
     * @param string|self $attribution
     *
     * @return bool
     */
    public function is($attribution): bool
    {
        if (is_string($attribution)) {
            return $this->name === $attribution;
        }

        if ($attribution instanceof self) {
            return $this->name === $attribution->name();
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function build(): string
    {
        if (empty($this->name)){
            return '';
        }

        if (is_null($this->value)){
            return sprintf('%s', $this->name);
        }

        if ($this->isProperty){
            return sprintf(':%s="%s"', $this->name, $this->value);
        } else{
            return sprintf('%s="%s"', $this->name, $this->value);
        }
    }
}