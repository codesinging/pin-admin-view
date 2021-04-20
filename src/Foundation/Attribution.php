<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Foundation;

class Attribution extends Buildable
{
    protected $name;

    protected $value;

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

    }

    public function build(): string
    {

    }
}