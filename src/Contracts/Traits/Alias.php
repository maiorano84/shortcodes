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
     * @return void
     */
    public function aliasHelper(string $alias): void
    {
        if (!($this instanceof AliasInterface)) {
            throw RegisterException::noAlias();
        }
        if (!$alias) {
            throw RegisterException::blank();
        }
        if ($this instanceof ContainerAwareInterface && $this->isBound()) {
            if (!$this->getManager()->isRegistered($alias)) {
                $this->getManager()->register($this, $alias);
            }
        }
    }

    /**
     * @return string[]
     */
    public function getAlias(): array
    {
        return $this->alias;
    }
}
