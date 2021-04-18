<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Foundation;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Support\Str;

class Builder extends Buildable
{
    use Directive;

    /**
     * The builder's base tag.
     *
     * @var string
     */
    protected $baseTag = '';

    /**
     * The prefix of the Builder's tag.
     *
     * @var string
     */
    protected $tagPrefix = '';

    /**
     * If the builder has a closing tag.
     *
     * @var bool
     */
    protected $closing = true;

    /**
     * If the builder has linebreak between the opening tag, content and the closing tag.
     *
     * @var bool
     */
    protected $linebreak = false;

    /**
     * The Css instance.
     *
     * @var Css
     */
    public $css;

    /**
     * The Style instance.
     *
     * @var Style
     */
    public $style;

    /**
     * The Attribute instance.
     *
     * @var Attribute
     */
    public $attribute;

    /**
     * The builder's initial attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The property instance.
     *
     * @var Repository
     */
    public $property;

    /**
     * The initial properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * The builder configuration repository.
     *
     * @var Repository
     */
    public $config;

    /**
     * The builder's initial configuration.
     *
     * @var array
     */
    protected $configs = [];

    /**
     * The Content instance.
     *
     * @var Content
     */
    public $content;

    /**
     * Whether the builder is buildable.
     *
     * @var bool
     */
    protected $buildable = true;

    /**
     * The namespace in the view's data.
     *
     * @var string
     */
    const BUILDER_NAMESPACE = 'builders';

    /**
     * The builder index.
     *
     * @var int
     */
    protected static $builderCount = 0;

    /**
     * The builder index.
     *
     * @var int
     */
    protected $builderIndex;

    /**
     * The builder id.
     *
     * @var string
     */
    protected $builderId;

    /**
     * All the builders.
     *
     * @var Builder[]
     */
    protected static $builders = [];

    /**
     * Builder constructor.
     *
     * @param string|array|null $tag
     * @param array|string|null $attributes
     * @param string|array|Buildable|Closure|null $content
     * @param bool|null $closing
     * @param bool|null $linebreak
     */
    public function __construct($tag = null, $attributes = null, $content = null, bool $closing = null, bool $linebreak = null)
    {
        $this->builderIndex = ++self::$builderCount;

        if (is_string($attributes)) {
            $content = $attributes;
            $attributes = null;
        }

        if (is_string($tag)) {
            $this->baseTag($tag);
        } elseif (is_array($tag)) {
            $attributes = $tag;
        }

        is_bool($closing) and $this->closing($closing);
        is_bool($linebreak) and $this->linebreak($linebreak);

        $this->css = new Css();
        $this->style = new Style();

        $this->attribute = new Attribute($this->attributes, $attributes);
        $this->property = new Repository($this->properties);
        $this->config = new Repository($this->configs);
        $this->content = new Content($content);

        $this->initialize();
    }

    /**
     * Get the builder count.
     *
     * @return int
     */
    public function builderCount(): int
    {
        return self::$builderCount;
    }

    /**
     * Get the builder index.
     *
     * @return int
     */
    public function builderIndex(): int
    {
        return $this->builderIndex;
    }

    /**
     * Get the automatic builder id.
     *
     * @return string
     */
    public function autoBuilderId(): string
    {
        return sprintf('comp_%s_%s', $this->builderIndex(), str_replace('-', '_', $this->fullTag()));
    }

    /**
     * Get or set the builder id.
     *
     * @param string|null $builderId
     *
     * @return string|$this
     */
    public function builderId(string $builderId = null)
    {
        if (is_null($builderId)) {
            return $this->builderId ?: $this->autoBuilderId();
        }

        $this->builderId = $builderId;
        return $this;
    }

    /**
     * Get the automatic base tag based on class name.
     *
     * @return string
     */
    public function autoBaseTag(): string
    {
        return Str::kebab(class_basename($this));
    }

    /**
     * Set or get the builder's base tag.
     *
     * @param string|null $tag
     *
     * @return $this|string
     */
    public function baseTag(string $tag = null)
    {
        if (is_null($tag)) {
            return $this->baseTag ?: $this->autoBaseTag();
        }
        $this->baseTag = $tag;
        return $this;
    }

    /**
     * Get the builder's tag with prefix.
     *
     * @return string
     */
    public function fullTag(): string
    {
        return $this->tagPrefix . $this->baseTag();
    }

    /**
     * Set builder's closing attribute.
     *
     * @param bool $closing
     *
     * @return $this
     */
    public function closing(bool $closing = true): self
    {
        $this->closing = $closing;
        return $this;
    }

    /**
     * Set builder's linebreak attribute.
     *
     * @param bool $linebreak
     *
     * @return $this
     */
    public function linebreak(bool $linebreak = true): self
    {
        $this->linebreak = $linebreak;
        return $this;
    }

    /**
     * Add css classes.
     *
     * @param string|array|Css|Closure ...$classes
     *
     * @return Css|$this
     */
    public function css(...$classes)
    {
        if (empty($classes)) {
            return $this->css;
        }
        $this->css->add(...$classes);
        return $this;
    }

    /**
     * Add styles or get Style instance.
     *
     * @param string|array|Style|Closure ...$styles
     *
     * @return Style|$this
     */
    public function style(...$styles)
    {
        if (empty($styles)) {
            return $this->style;
        }
        $this->style->add(...$styles);
        return $this;
    }

    /**
     * Set or get attribute value.
     * Get the Attribute instance.
     *
     * @param null|string|array $key
     * @param mixed $value
     *
     * @return $this|Attribute|mixed
     */
    public function attr($key = null, $value = null)
    {
        if (is_null($key)) {
            return $this->attribute;
        }

        if (is_string($key)) {
            return $this->attribute->get($key, $value);
        }

        if (is_array($key)) {
            $this->attribute->set($key);
            return $this;
        }

        return $this->attribute;
    }

    /**
     * Get all attributes items of the builder.
     *
     * @return array
     */
    public function attributes(): array
    {
        return $this->attribute->all();
    }

    /**
     * Set property values.
     *
     * @param array|string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value = null): self
    {
        $this->property->set($key, $value);
        return $this;
    }

    /**
     * Get the specified property value.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return array|string|int|mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->property->get($key, $default);
    }

    /**
     * Get all properties of the builder.
     *
     * @return array
     */
    public function properties(): array
    {
        return $this->property->all();
    }

    /**
     * Set/get configuration value or get the configuration repository instance.
     *
     * @param null|string|array $key
     * @param mixed $value
     *
     * @return $this|Repository|mixed
     */
    public function config($key = null, $value = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        if (is_string($key)) {
            return $this->config->get($key, $value);
        }

        if (is_array($key)) {
            $this->config->set($key);
            return $this;
        }

        return $this->config;
    }

    /**
     * Get all configurations.
     *
     * @return array
     */
    public function configs(): array
    {
        return $this->config->all();
    }

    /**
     * Add contents to the content flow.
     *
     * @param string|array|Buildable|Closure ...$contents
     *
     * @return $this
     */
    public function add(...$contents): self
    {
        $this->content->add(...$contents);
        return $this;
    }

    /**
     * Prepend contents to the beginning of the content flow.
     *
     * @param string|array|Buildable|Closure ...$contents
     *
     * @return $this
     */
    public function prepend(...$contents): self
    {
        $this->content->prepend(...$contents);
        return $this;
    }

    /**
     * Add a interpolation content to the content flow.
     *
     * @param string $content
     *
     * @return $this
     */
    public function interpolation(string $content): self
    {
        $this->add(sprintf('{{ %s }}', $content));
        return $this;
    }

    /**
     * Add a named slot content to the content flow.
     *
     * @param string $name
     * @param string|array|Builder|Buildable|Closure $content
     * @param string|null $prop
     *
     * @return $this
     */
    public function slot(string $name, $content, string $prop = null): self
    {
        if ($content instanceof Closure) {
            $content = call_closure($content, new Content());
        }

        $attributes = is_null($prop) ? ["#{$name}"] : ["#{$name}" => $prop];

        $builder = new self('template', $attributes, $content);

        $this->add($builder);

        return $this;
    }

    /**
     * Get the builder's contents.
     *
     * @return string
     */
    public function contents(): string
    {
        return $this->content->build();
    }

    /**
     * Get the builder key of the data in the view.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function builderKey(string $path = null): string
    {
        return self::BUILDER_NAMESPACE . '.' . $this->builderId() . ($path ? '.' . $path : '');
    }

    /**
     * Get the property key.
     *
     * @param string|null $key
     *
     * @return string
     */
    public function propertyKey(string $key = null): string
    {
        return $this->builderKey('properties' . ($key ? '.' . $key : ''));
    }

    /**
     * Get the configuration key.
     *
     * @param string|null $key
     *
     * @return string
     */
    public function configKey(string $key = null): string
    {
        return $this->builderKey('configs' . ($key ? '.' . $key : ''));
    }

    /**
     * Determine whether the builder is buildable.
     *
     * @param bool $buildable
     *
     * @return $this
     */
    public function buildable(bool $buildable = true): self
    {
        $this->buildable = $buildable;
        return $this;
    }

    /**
     * Whether the builder is buildable.
     *
     * @return bool
     */
    public function isBuildable(): bool
    {
        return $this->buildable;
    }

    /**
     * Get all the builders.
     *
     * @return Builder[]
     */
    public static function builders(): array
    {
        return self::$builders;
    }

    /**
     * The methods to set properties.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return $this
     */
    public function __call(string $name, array $arguments): self
    {
        if (Str::contains($name, '_')) {
            $this->set(Str::kebab(Str::before($name, '_')), Str::after($name, '_'));
        } elseif (preg_match('/on[A-Z][a-zA-Z]+/', $name)) {
            $event = lcfirst(substr($name, 2));
            if (count($arguments) > 1) {
                $handler = $arguments[0] . '(';
                array_shift($arguments);
                $handler .= implode(', ', $arguments);
                $handler .= ')';
            } else {
                $handler = $arguments[0] ?? $event;
            }
            $this->vOn($event, $handler);
        } else {
            $this->set(Str::kebab($name), $arguments[0] ?? true);
        }

        return $this;
    }

    /**
     * Initialize the builder.
     */
    protected function initialize(): void
    {
    }

    /**
     * Ready to build the builder.
     */
    protected function ready(): void
    {
    }

    /**
     * Build content as a string of the object.
     *
     * @return string
     */
    public function build(): string
    {
        if (!$this->isBuildable()) {
            return '';
        }

        $this->ready();

        self::$builders[] = $this;

        if (!$this->css->isEmpty()) {
            $this->attr(['class' => $this->css->build()]);
        }

        if (!$this->style->isEmpty()) {
            $this->attr(['style' => $this->style->build()]);
        }

        if (!empty($this->properties())) {
            $this->attr(['v-bind' => $this->builderKey('properties')]);
        }

        $this->attribute->placeholder([
            '*.' => $this->builderKey() . '.',
            '\*\.' => '*.',
            '@.' => $this->propertyKey() . '.',
            '\@\.' => '@.',
            '#.' => $this->configKey() . '.',
            '\#\.' => '#.',
        ]);

        if ($this->linebreak) {
            $this->content->linebreak();
        }

        return sprintf(
            '<%s%s>%s%s%s%s',
            $this->fullTag(),
            $this->attribute->isEmpty() ? '' : ' ' . $this->attribute->build(),
            $this->linebreak && !$this->content->isEmpty() ? PHP_EOL : '',
            $this->content->build(),
            $this->linebreak && $this->closing ? PHP_EOL : '',
            $this->closing ? '</' . $this->fullTag() . '>' : ''
        );
    }
}