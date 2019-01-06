<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Manager\ManagerInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Trait ContainerAware
 * Assists in satisfying the ContainerAwareInterface requirements
 * Exposes the management container and its public members
 * @package Maiorano\Shortcodes\Contracts\Traits
 */
trait ContainerAware
{
    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @param ManagerInterface $manager
     * @return void
     * @see \Maiorano\Shortcodes\Contracts\ContainerAwareInterface::bind()
     */
    public function bind(ManagerInterface $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return bool
     * @see \Maiorano\Shortcodes\Contracts\ContainerAwareInterface::isBound()
     */
    public function isBound(): bool
    {
        return $this->manager instanceof ManagerInterface;
    }

    /**
     * Convenience method
     * Utilizes manager's implementation of hasShortcode
     * Limits search to this shortcode's context
     * @param string $content
     * @return bool
     * @throws RegisterException
     */
    public function hasShortcode($content)
    {
        if (!($this->isBound())) {
            $e = sprintf(RegisterException::MISSING, $this->name);
            throw new RegisterException($e);
        }

        return $this->manager->hasShortcode($content, $this->getContext());
    }

    /**
     * Convenience method
     * Utilizes manager's implementation of doShortcode
     * Limits search to this shortcode's context
     * @param string $content
     * @param bool $deep
     * @return string
     * @throws RegisterException
     */
    public function doShortcode($content, $deep = false)
    {
        if (!($this->isBound())) {
            $e = sprintf(RegisterException::MISSING, $this->name);
            throw new RegisterException($e);
        }

        return $this->manager->doShortcode($content, $this->getContext(), $deep);
    }

    public function getManager(): ManagerInterface
    {
        return $this->manager;
    }

    /**
     * Utility method
     * @return string|array
     */
    private function getContext()
    {
        $context = $this->name;
        if ($this instanceof AliasInterface) {
            $context = $this->alias;
            $context[] = $this->name;
        }

        return $context;
    }
}
