<?php
namespace Maiorano\Shortcodes\Contracts\Traits;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Trait Alias
 * Assists in satisfying the AliasInterface requirements
 * Allows shortcodes to be aliased
 * @package Maiorano\Shortcodes\Contracts\Traits
 */
trait Alias
{

    /**
     * @param string $alias
     * @return \Maiorano\Shortcodes\Contracts\ShortcodeInterface
     * @throws RegisterException
     */
    public function alias($alias)
    {
        if (!($this instanceof AliasInterface)) {
            throw new RegisterException(RegisterException::NO_ALIAS);
        }
        if (!$alias) {
            throw new RegisterException(RegisterException::BLANK);
        }

        if (!in_array($alias, $this->alias)) {
            $this->alias[] = $alias;
        }

        if ($this instanceof ContainerAwareInterface && $this->isBound()) {
            //$this->isBound();
            //if (!$this->manager->isRegistered($alias)) {
                //$this->manager->register($this, $alias, false);
            //}
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
