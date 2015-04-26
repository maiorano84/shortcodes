<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeTrait;
use Maiorano\Shortcodes\Contracts\AttributeTrait;
use Maiorano\Shortcodes\Contracts\CallableTrait;
use Maiorano\Shortcodes\Contracts\AliasTrait;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Contracts
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface, AliasInterface
{
    use ShortcodeTrait, AttributeTrait, CallableTrait, AliasTrait;

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
     * @param string $name
     * @param array $atts
     * @param callable $callback
     */
    public function __construct($name, $atts = [], Callable $callback = null)
    {
        $this->name = $name;
        $this->attributes = (array)$atts;
        $this->callback = $callback;
    }
}
