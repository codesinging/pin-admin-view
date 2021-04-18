<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Foundation;

use Illuminate\Support\Str;

trait Directive
{
    /**
     * Add a `v-text` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vText(string $value): self
    {
        $this->attr(['v-text' => $value]);
        return $this;
    }

    /**
     * Add a `v-html` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vHtml(string $value): self
    {
        $this->attr(['v-html' => $value]);
        return $this;
    }

    /**
     * Add a `v-show` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vShow(string $value): self
    {
        $this->attr(['v-show' => $value]);
        return $this;
    }

    /**
     * Add a `v-if` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vIf(string $value): self
    {
        $this->attr(['v-if' => $value]);
        return $this;
    }

    /**
     * Add a `v-else-if` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vElseIf(string $value): self
    {
        $this->attr(['v-else-if' => $value]);
        return $this;
    }

    /**
     * Add a `v-else` directive.
     *
     * @return $this
     */
    public function vElse(): self
    {
        $this->attr(['v-else']);
        return $this;
    }

    /**
     * Add a `v-for` directive.
     *
     * @param string $value
     *
     * @return $this
     */
    public function vFor(string $value): self
    {
        $this->attr(['v-for' => $value]);
        return $this;
    }

    /**
     * Add a `v-on` directive.
     *
     * @param string|array $event
     * @param string|null $handler
     *
     * @return $this
     */
    public function vOn($event, string $handler = null): self
    {
        is_string($event) and $event = [$event => $handler];

        if (is_array($event)) {
            foreach ($event as $key => $value) {
                $this->attr([Str::start(Str::kebab($key), '@') => $value]);
            }
        }
        return $this;
    }

    /**
     * Add a `v-on:click` directive.
     *
     * @param string $handler
     * @param string|null $modifier
     *
     * @return $this
     */
    public function vClick(string $handler, string $modifier = null): self
    {
        $event = 'click' . ($modifier ? '.' . $modifier : '');
        return $this->vOn($event, $handler);
    }

    /**
     * Add a `v-on` directive which assign a value to a property.
     *
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function vAssign(string $name, $value): self
    {
        if ($value === true) {
            $value = 'true';
        } elseif ($value === false) {
            $value = 'false';
        }

        $handler = sprintf('%s = %s', $name, $value);
        return $this->vClick($handler);
    }

    /**
     * Add a `v-bind` directive.
     *
     * @param string|array $name
     * @param null|mixed $value
     *
     * @return $this
     */
    public function vBind($name, $value = null): self
    {
        is_string($name) and $name = [$name => $value];

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->attr([Str::start($key, ':') => $value]);
            }
        }
        return $this;
    }

    /**
     * Add a `v-model` directive.
     *
     * @param string $model
     * @param string|null $modifier
     *
     * @return $this
     */
    public function vModel(string $model, string $modifier = null): self
    {
        $key = 'v-model' . ($modifier ? '.' . $modifier : '');
        $this->attr([$key => $model]);
        return $this;
    }

    /**
     * Add a `v-pre` directive.
     *
     * @return $this
     */
    public function vPre(): self
    {
        $this->attr(['v-pre']);
        return $this;
    }

    /**
     * Add a `v-cloak` directive.
     *
     * @return $this
     */
    public function vCloak(): self
    {
        $this->attr(['v-cloak']);
        return $this;
    }

    /**
     * Add a `v-once` directive.
     *
     * @return $this
     */
    public function vOnce(): self
    {
        $this->attr(['v-once']);
        return $this;
    }

    /**
     * Add ref attribute.
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function ref(string $name = null): self
    {
        $this->attr(['ref' => $name ?? $this->builderId()]);
        return $this;
    }
}