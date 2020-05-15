<?php

namespace Maiorano\Shortcodes\Library;

use Closure;
use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Contracts\Traits;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Creation of Shortcodes programmatically.
 */
final class SimpleShortcode implements Contracts\AttributeInterface, Contracts\AliasInterface, Contracts\ContainerAwareInterface
{
    use Traits\Attribute;
    use Traits\Alias;
    use Traits\ContainerAware;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed[]
     */
    protected $attributes;

    /**
     * @var string[]
     */
    protected $alias = [];

    /**
     * @var Closure|null
     */
    protected $callback;

    /**
     * @param string       $name
     * @param mixed[]|null   $atts
     * @param Closure|null $callback
     */
    public function __construct($name, $atts = [], Closure $callback = null)
    {
        $this->name = $name;
        $this->attributes = (array) $atts;
        $this->callback = $callback;
    }

    /**
     * @param string|null $content
     * @param mixed[]     $atts
     *
     * @return string
     */
    public function handle(?string $content = null, array $atts = []): string
    {
        if (is_null($this->callback)) {
            return (string) $content;
        }
        $callback = $this->callback->bindTo($this, $this);

        return $callback($content, $atts);
    }

    /**
     * @param string $alias
     *
     * @throws RegisterException
     *
     * @return Contracts\AliasInterface
     */
    public function alias(string $alias): Contracts\AliasInterface
    {
        if (!in_array($alias, $this->alias)) {
            $this->alias[] = $alias;
        }
        $this->aliasHelper($alias);

        return $this;
    }
}
