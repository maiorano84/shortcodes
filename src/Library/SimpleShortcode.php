<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;

use Maiorano\Shortcodes\Contracts\Traits\Shortcode;
use Maiorano\Shortcodes\Contracts\Traits\Attribute;
use Maiorano\Shortcodes\Contracts\Traits\CallableTrait;
use Maiorano\Shortcodes\Contracts\Traits\Alias;
use Maiorano\Shortcodes\Contracts\Traits\ContainerAware;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Contracts
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface, AliasInterface, ContainerAwareInterface
{
    use Shortcode, Attribute, CallableTrait, Alias, ContainerAware;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var Callable|null
     */
    protected $callback;

    /**
     * @var array
     */
    protected $alias = [];

    /**
     * @param string $name
     * @param array|null $atts
     * @param Callable|null $callback
     */
    public function __construct($name, $atts = [], Callable $callback = null)
    {
        $this->name = $name;
        $this->attributes = (array)$atts;
        $this->callback = $callback;
    }
}
