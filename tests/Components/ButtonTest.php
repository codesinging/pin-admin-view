<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Components;

use CodeSinging\PinAdminView\Components\Button;
use Orchestra\Testbench\TestCase;

class ButtonTest extends TestCase
{
    public function testButton()
    {
        self::assertEquals('<el-button></el-button>', new Button());
    }
}