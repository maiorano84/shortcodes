<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Trait AliasTrait
 * Assists in satisfying the AliasInterface requirements
 * Allows shortcodes to be aliased
 * @package Maiorano\Shortcodes\Contracts
 */
trait AliasTrait
{

    /**
     * @param string $alias
     * @return ShortcodeInterface
     * @throws RegisterException
     */
    public function alias($alias)
    {
        if (!($this instanceof AliasInterface)) {
            throw new RegisterException(RegisterException::NO_ALIAS);
        }

        $alias = (string)$alias;
        if (!in_array($alias, $this->alias)) {
            $this->alias[] = $alias;
        }

        if ($this->manager instanceof ManagerInterface) {
            $this->manager->register($this, $alias, false);
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
