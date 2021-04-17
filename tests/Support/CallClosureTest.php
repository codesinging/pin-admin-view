<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Support;

use CodeSinging\PinAdminView\Support\CallClosure;
use Orchestra\Testbench\TestCase;

class CallClosureTest extends TestCase
{
    use CallClosure;

    public function testCallClosure()
    {
        self::assertEquals('a', $this->callClosure(function () {
            return 'a';
        }));

        self::assertEquals('a', $this->callClosure(function () {
        }, 'a'));
    }
}
