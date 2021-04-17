<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Foundation;

use CodeSinging\PinAdminView\Foundation\Element;
use Orchestra\Testbench\TestCase;

class ElementTest extends TestCase
{
    public function testBuild()
    {
        self::assertEquals('<div></div>', new Element());
        self::assertEquals('<span></span>', new Element('span'));
    }
}
