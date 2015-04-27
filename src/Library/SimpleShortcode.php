<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeTrait;
use Maiorano\Shortcodes\Contracts\AttributeTrait;
use Maiorano\Shortcodes\Contracts\CallableTrait;
use Maiorano\Shortcodes\Contracts\AliasTrait;
use Maiorano\Shortcodes\Contracts\ContainerAwareTrait;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\Shortcodes\Contracts
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface, AliasInterface, ContainerAwareInterface
{
    use ShortcodeTrait, AttributeTrait, CallableTrait, AliasTrait, ContainerAwareTrait;

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
     * @var array
     */
    protected $alias = [];

    /**
     * @var \Maiorano\Shortcodes\Manager\ManagerInterface
     */
    protected $manager;

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
