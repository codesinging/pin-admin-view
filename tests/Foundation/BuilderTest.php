<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Tests\Foundation;

use CodeSinging\PinAdminView\Foundation\Attribute;
use CodeSinging\PinAdminView\Foundation\Builder;
use CodeSinging\PinAdminView\Foundation\Content;
use CodeSinging\PinAdminView\Foundation\Css;
use CodeSinging\PinAdminView\Foundation\Style;
use Illuminate\Config\Repository;
use Orchestra\Testbench\TestCase;

class BuilderTest extends TestCase
{
    public function testClassAttributes()
    {
        self::assertEquals('span', (new TestClassAttribute())->baseTag());
        self::assertEquals('el-span', (new TestClassAttribute())->fullTag());
        self::assertStringNotContainsString('</span>', (new TestClassAttribute())->buildable()->build());
        self::assertStringContainsString(PHP_EOL, (new TestClassAttribute())->buildable()->closing()->build());
        self::assertInstanceOf(Css::class, (new TestClassAttribute())->css);
        self::assertInstanceOf(Style::class, (new TestClassAttribute())->style);
        self::assertInstanceOf(Attribute::class, (new TestClassAttribute())->attribute);
        self::assertEquals(['id' =>1], (new TestClassAttribute())->attributes());
        self::assertInstanceOf(Repository::class, (new TestClassAttribute())->property);
        self::assertEquals(['id' =>1], (new TestClassAttribute())->properties());
        self::assertInstanceOf(Repository::class, (new TestClassAttribute())->config);
        self::assertEquals(['id' =>1], (new TestClassAttribute())->configs());
        self::assertEquals('', new TestClassAttribute());
        self::assertInstanceOf(Content::class, (new TestClassAttribute())->content);
    }

    public function testBuilderCount()
    {
        $builderOneCount = (new Builder())->builderCount();
        $builderTwoCount = (new Builder())->builderCount();

        self::assertIsInt($builderOneCount);
        self::assertIsInt($builderTwoCount);
        self::assertEquals($builderTwoCount, $builderOneCount + 1);
    }

    public function testBuilderIndex()
    {
        $builderOneIndex = (new Builder())->builderIndex();
        $builderTwoIndex = (new Builder())->builderIndex();

        self::assertIsInt($builderOneIndex);
        self::assertIsInt($builderTwoIndex);
        self::assertEquals($builderTwoIndex, $builderOneIndex + 1);
    }

    public function testAutoBuilderId()
    {
        self::assertMatchesRegularExpression("/comp_[0-9]+_div/", (new Builder('div'))->builderId());
    }

    public function testBuilderId()
    {
        self::assertMatchesRegularExpression("/[a-z][a-z0-9]+/", (new Builder())->builderId());
        self::assertEquals('table', (new Builder())->builderId('table')->builderId());
    }

    public function testAutoBaseTag()
    {
        self::assertEquals('test-auto-base-tag', (new TestAutoBaseTag())->autoBaseTag());
    }

    public function testBaseTag()
    {
        self::assertEquals('builder', (new Builder())->baseTag());
        self::assertEquals('span', (new Builder())->baseTag('span')->baseTag());
        self::assertEquals('span', (new TestBaseTag())->baseTag());
        self::assertEquals('span', (new Builder())->baseTag('span')->baseTag());
    }

    public function testFullTag()
    {
        self::assertEquals('builder', (new Builder())->fullTag());
        self::assertEquals('el-button', (new TestFullTag('button'))->fullTag());
    }

    public function testClosing()
    {
        self::assertEquals('<img>', (new Builder('img'))->closing(false));
    }

    public function testLinebreak()
    {
        self::assertEquals('<div>' . PHP_EOL . '</div>', (new Builder('div'))->linebreak());
        self::assertEquals('<div>' . PHP_EOL . 'content' . PHP_EOL . '</div>', (new Builder('div', 'content'))->linebreak());
    }

    public function testCss()
    {
        self::assertInstanceOf(Css::class, (new Builder())->css());
        self::assertEquals('<div class="p"></div>', (new Builder('div'))->css('p'));
        self::assertEquals('<div class="p m"></div>', (new Builder('div'))->css('p', 'm'));
    }

    public function testStyle()
    {
        self::assertInstanceOf(Style::class, (new Builder())->style());
        self::assertEquals('<div style="width:1px;"></div>', (new Builder('div'))->style(['width' => '1px'])->build());
        self::assertEquals('<div style="width:1px; height:1px;"></div>', (new Builder('div'))->style(['width' => '1px'], ['height' => '1px'])->build());
    }

    public function testAttr()
    {
        self::assertInstanceOf(Attribute::class, (new Builder())->attr());
        self::assertEquals('app', (new Builder(['id' => 'app']))->attr('id'));
        self::assertEquals('app', (new Builder())->attr(['id' => 'app'])->attr('id'));
    }

    public function testAttributes()
    {
        self::assertEquals(['name' => 'Name',], (new Builder(['name' => 'Name',]))->attributes());
    }

    public function testSet()
    {
        self::assertEquals(['id' => 1], (new Builder())->set(['id' => 1])->properties());
        self::assertEquals(['id' => 1], (new Builder())->set('id', 1)->properties());
    }

    public function testGet()
    {
        self::assertEquals(1, (new Builder())->set(['id' => 1])->get('id'));
    }

    public function testProperties()
    {
        self::assertEquals(['id' => 1], (new Builder())->set(['id' => 1])->properties());
    }

    public function testConfig()
    {
        self::assertInstanceOf(Repository::class, (new Builder())->config());
        self::assertEquals(1, (new Builder())->config(['id' => 1])->config('id'));
        self::assertEquals(2, (new Builder())->config(['id' => 1])->config('is', 2));
    }

    public function testConfigs()
    {
        self::assertEquals(['id' => 1], (new Builder())->config(['id' => 1])->configs());
    }

    public function testAdd()
    {
        self::assertEquals('hello', (new Builder())->add('hello')->contents());
        self::assertEquals('hello world', (new Builder())->add('hello', ' ', 'world')->contents());
        self::assertEquals('ab', (new Builder())->add(['a', 'b'])->contents());
        self::assertEquals('<div></div>', (new Builder())->add(new Builder('div'))->contents());
    }

    public function testPrepend()
    {
        self::assertEquals('ab', (new Builder('div', null, 'b'))->prepend('a')->contents());
        self::assertEquals('abc', (new Builder('div', null, 'c'))->prepend('a', 'b')->contents());
        self::assertEquals('abc', (new Builder('div', null, 'c'))->prepend('b')->prepend('a')->contents());
    }

    public function testInterpolation()
    {
        self::assertEquals('{{ title }}', (new Builder())->interpolation('title')->contents());
    }

    public function testSlot()
    {
        self::assertEquals('<template #title>Title</template>', (new Builder())->slot('title', 'Title')->contents());
        self::assertEquals('<template #title>hello world</template>', (new Builder())->slot('title', ['hello', ' ', 'world'])->contents());
        self::assertEquals('<template #title><div>Title</div></template>', (new Builder())->slot('title', new Builder('div', 'Title'))->contents());
        self::assertEquals('<template #title>Title</template>', (new Builder())->slot('title', function (){
            return 'Title';
        })->contents());
        self::assertEquals('<template #title>Title</template>', (new Builder())->slot('title', function (Content $content){
            $content->add('Title');
        })->contents());
        self::assertEquals('<template #title="prop">Title</template>', (new Builder())->slot('title', 'Title', 'prop')->contents());
    }

    public function testContents()
    {
        self::assertEquals('hello', (new Builder())->add('hello')->contents());
    }

    public function testBuilderKey()
    {
        $builder = new Builder();
        $namespace = $builder::BUILDER_NAMESPACE . '.' . $builder->builderId();

        self::assertEquals($namespace, $builder->builderKey());
        self::assertEquals($namespace . '.properties', $builder->builderKey('properties'));
        self::assertEquals($namespace . '.properties.visible', $builder->builderKey('properties.visible'));
    }

    public function testPropertyKey()
    {
        $builder = new Builder();
        $namespace = $builder::BUILDER_NAMESPACE . '.' . $builder->builderId();

        self::assertEquals($namespace . '.properties', $builder->propertyKey());
        self::assertEquals($namespace . '.properties.visible', $builder->propertyKey('visible'));
    }

    public function testConfigKey()
    {
        $builder = new Builder();
        $namespace = $builder::BUILDER_NAMESPACE . '.' . $builder->builderId();

        self::assertEquals($namespace . '.configs', $builder->configKey());
        self::assertEquals($namespace . '.configs.visible', $builder->configKey('visible'));
    }

    public function testBuildable()
    {
        self::assertFalse((new Builder())->buildable(false)->isBuildable());
        self::assertTrue((new Builder())->buildable()->isBuildable());
    }

    public function testIsBuildable()
    {
        self::assertTrue((new Builder())->isBuildable());
    }

    public function testBuilders()
    {
        $builder = (new Builder('div'))->build();
        self::assertInstanceOf(Builder::class, last(Builder::builders()));
        self::assertEquals($builder, last(Builder::builders()));
    }

    public function testInitialize()
    {
        self::assertEquals('span', (new TestInitialize())->baseTag());
    }

    public function testReady()
    {
        self::assertEquals('<span></span>', (new TestReady())->build());
    }

    public function testBuildAboutBuildable()
    {
        self::assertEquals('<div></div>', (new Builder('div'))->build());
        self::assertSame('', (new Builder('div'))->buildable(false)->build());
    }

    public function testBuildAboutCssAttribute()
    {
        self::assertEquals('<div class="m p"></div>', (new Builder('div'))->css('m p'));
    }

    public function testBuildAboutStyleAttribute()
    {
        self::assertEquals('<div style="color:red;"></div>', (new Builder('div'))->style(['color' => 'red']));
    }

    public function testBuildAboutProperties()
    {
        $build = (new Builder('div'))->set('id', 1);
        self::assertEquals(
            '<div v-bind="' . Builder::BUILDER_NAMESPACE . '.' . $build->builderId() . '.properties"></div>',
            $build->build()
        );
        self::assertEquals(['id' => 1], $build->properties());
    }

    public function testPlaceholderInConstruct()
    {
        $builder = new Builder('div', [':visible' => '*.visible']);
        self::assertEquals('<div :visible="' . Builder::BUILDER_NAMESPACE . '.' . $builder->builderId() . '.visible"></div>', $builder->build());
    }

    public function testPlaceholderInSetAttr()
    {
        $builder = new Builder('div');
        self::assertEquals('<div :visible="' . Builder::BUILDER_NAMESPACE . '.' . $builder->builderId() . '.visible"></div>', $builder->attr([':visible' => '*.visible'])->build());
    }

    public function testPropertyPlaceholder()
    {
        $builder = new Builder('div', [':visible' => '@.visible']);
        self::assertEquals('<div :visible="' . Builder::BUILDER_NAMESPACE . '.' . $builder->builderId() . '.properties.visible"></div>', $builder->build());
    }

    public function testConfigPlaceholder()
    {
        $builder = new Builder('div', [':visible' => '#.visible']);
        self::assertEquals('<div :visible="' . Builder::BUILDER_NAMESPACE . '.' . $builder->builderId() . '.configs.visible"></div>', $builder->build());
    }
}

class TestClassAttribute extends Builder
{
    protected $baseTag = 'span';
    protected $tagPrefix = 'el-';
    protected $closing = false;
    protected $linebreak = true;
    protected $attributes = ['id' => 1];
    protected $properties = ['id' => 1];
    protected $configs = ['id' => 1];
    protected $buildable = false;
}

class TestAutoBaseTag extends Builder
{
}

class TestBaseTag extends Builder
{
    protected $baseTag = 'span';
}

class TestFullTag extends Builder
{
    protected $tagPrefix = 'el-';
}

class TestInitialize extends Builder
{
    protected function initialize(): void
    {
        parent::initialize();
        $this->baseTag('span');
    }
}

class TestReady extends Builder
{
    protected function ready(): void
    {
        parent::ready();
        $this->baseTag('span');
    }
}