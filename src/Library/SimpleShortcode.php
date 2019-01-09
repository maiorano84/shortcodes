<?php

namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Contracts\Traits;
use Closure;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Contracts
 */
class SimpleShortcode implements Contracts\AttributeInterface, Contracts\AliasInterface, Contracts\ContainerAwareInterface
{
    use Traits\Attribute, Traits\Alias, Traits\ContainerAware;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $alias = [];

    /**
     * @var Closure|null
     */
    protected $callback;

    /**
     * @param string $name
     * @param array|null $atts
     * @param Closure|null $callback
     */
    public function __construct($name, $atts = [], Closure $callback = null)
    {
        $this->name = $name;
        $this->attributes = (array)$atts;
        $this->callback = $callback;
    }

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     */
    public function handle(?string $content = null, array $atts = []): string
    {
        if (is_null($this->callback)) {
            return (string)$content;
        }
        $callback = $this->callback->bindTo($this, $this);

        return $callback($content, $atts);
    }
}
