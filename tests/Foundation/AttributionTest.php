<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Foundation;

use CodeSinging\PinAdminView\Foundation\Attribution;
use Orchestra\Testbench\TestCase;

class AttributionTest extends TestCase
{
    public function testName()
    {
        self::assertEquals("id", (new Attribution("id"))->name());
        self::assertEquals("id", (new Attribution(":id"))->name());
        self::assertEquals("id", (new Attribution("id", "3"))->name());
        self::assertEquals("id", (new Attribution("id", ":3"))->name());
        self::assertEquals("id", (new Attribution("id", 3))->name());
    }

    public function testValue()
    {
        self::assertNull((new Attribution('id'))->value());
        self::assertNull((new Attribution('id', null))->value());

        self::assertEquals('3', (new Attribution('id', ':3'))->value());
        self::assertEquals('id', (new Attribution('id', ':id'))->value());

        self::assertEquals('true', (new Attribution('disabled', true))->value());
        self::assertEquals('false', (new Attribution('disabled', false))->value());
        self::assertEquals('true', (new Attribution(':disabled'))->value());
        self::assertEquals('true', (new Attribution('disabled', null, true))->value());

        self::assertEquals('3', (new Attribution('id', 3))->value());
    }

    public function testIsProperty()
    {
        self::assertFalse((new Attribution("disabled"))->isProperty());
        self::assertFalse((new Attribution("title", "Title"))->isProperty());
        self::assertFalse((new Attribution("title", "\:Title"))->isProperty());
        self::assertFalse((new Attribution("title", "\\Title"))->isProperty());

        self::assertTrue((new Attribution("title", ":title"))->isProperty());
        self::assertTrue((new Attribution(":title", "title"))->isProperty());
        self::assertTrue((new Attribution(":age", "20"))->isProperty());
        self::assertTrue((new Attribution("age", ":20"))->isProperty());
        self::assertTrue((new Attribution("age", 20))->isProperty());
        self::assertTrue((new Attribution("disabled:", true))->isProperty());
        self::assertTrue((new Attribution("disabled:", false))->isProperty());
        self::assertTrue((new Attribution("disabled:", ':true'))->isProperty());
        self::assertTrue((new Attribution("disabled:", ':false'))->isProperty());
        self::assertTrue((new Attribution("data:", []))->isProperty());
        self::assertTrue((new Attribution('id', 'id', true))->isProperty());
    }

    public function testIs()
    {
        self::assertTrue((new Attribution("title"))->is("title"));
        self::assertTrue((new Attribution(":title"))->is("title"));
        self::assertTrue((new Attribution("title"))->is(new Attribution("title")));
        self::assertTrue((new Attribution("title"))->is(new Attribution(":title")));
        self::assertTrue((new Attribution(":title"))->is(new Attribution("title")));
        self::assertTrue((new Attribution(":title"))->is(new Attribution(":title")));
    }

    public function testBuild()
    {
        self::assertEmpty((new Attribution(""))->build());

        self::assertEquals("disabled", new Attribution("disabled"));
        self::assertEquals(':disabled="true"', new Attribution(":disabled"));

        self::assertEquals('title="Title"', (new Attribution("title", "Title")));
        self::assertEquals('title="true"', new Attribution("title", "true"));
        self::assertEquals('title="false"', new Attribution("title", "false"));
        self::assertEquals('title="20"', new Attribution("title", "20"));

        self::assertEquals(':disabled="true"', new Attribution("disabled", true));
        self::assertEquals(':disabled="false"', new Attribution("disabled", false));

        self::assertEquals(':age="20"', new Attribution("age", 20));
        self::assertEquals(':score="60.5"', new Attribution("score", 60.5));

        self::assertEquals(':class="["margin"]"', (new Attribution("class", ['margin'])));
        self::assertEquals(':data="{"title":"Title"}"', (new Attribution("data", ['title' => "Title"])));

        self::assertEquals(':title="title"', new Attribution("title", ":title"));
        self::assertEquals(':age="20"', new Attribution("age", ":20"));
        self::assertEquals(':disabled="true"', new Attribution("disabled", ":true"));
        self::assertEquals(':disabled="false"', new Attribution("disabled", ":false"));

        self::assertEquals('title=":title"', new Attribution("title", "\:title"));
        self::assertEquals('title="\\title"', new Attribution("title", "\\\\title"));

        self::assertEquals(':title="title"', new Attribution(":title", "title"));
        self::assertEquals(':age="22"', new Attribution(":age", "22"));
        self::assertEquals(':disabled="true"', new Attribution(":disabled", "true"));
        self::assertEquals(':disabled="false"', new Attribution(":disabled", "false"));

        self::assertEquals(':disabled="true"', new Attribution(":disabled"));

        self::assertEquals(':title="title"', new Attribution(":title", ":title"));
        self::assertEquals(':age="22"', new Attribution(":age", ":22"));
        self::assertEquals(':disabled="true"', new Attribution(":disabled", ":true"));
        self::assertEquals(':disabled="false"', new Attribution(":disabled", ":false"));

        self::assertEquals(':disabled="true"', new Attribution(":disabled", true));
        self::assertEquals(':disabled="false"', new Attribution(":disabled", false));
    }
}
