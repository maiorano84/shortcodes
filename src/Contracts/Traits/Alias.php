<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Trait Alias
 * Assists in satisfying the AliasInterface requirements
 * Allows shortcodes to be aliased.
 */
trait Alias
{
    /**
     * @param string $alias
     *
     * @throws RegisterException
     *
     * @return AliasInterface
     */
    public function alias(string $alias): AliasInterface
    {
        if (!($this instanceof AliasInterface)) {
            throw RegisterException::noAlias();
        }
        if (!$alias) {
            throw RegisterException::blank();
        }

        if (!in_array($alias, $this->alias)) {
            $this->alias[] = $alias;
        }

        if ($this instanceof ContainerAwareInterface && $this->isBound()) {
            if (!$this->getManager()->isRegistered($alias)) {
                $this->getManager()->register($this, $alias);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAlias(): array
    {
        return $this->alias;
    }
}
