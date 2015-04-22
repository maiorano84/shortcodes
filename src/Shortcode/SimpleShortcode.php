<?php
namespace Maiorano\Shortcodes\Shortcode;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Shortcode
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface
{
    use ShortcodeTrait;
    use AttributeTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var callable
     */
    protected $callback;

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