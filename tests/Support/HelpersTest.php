<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Support;

use Orchestra\Testbench\TestCase;

class HelpersTest extends TestCase
{
    public function testCallClosure()
    {
        self::assertEquals('a', call_closure(function () {
            return 'a';
        }));

        self::assertEquals('a', call_closure(function () {
        }, 'a'));
    }
}
