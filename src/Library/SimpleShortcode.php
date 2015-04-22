<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeTrait;
use Maiorano\Shortcodes\Contracts\AttributeTrait;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Contracts
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface
{
    use ShortcodeTrait;
    use AttributeTrait;

    /**
     * @var ShortcodeManagerInterface
     */
    protected $manager;

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