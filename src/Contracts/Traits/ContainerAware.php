<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Manager\ManagerInterface;

/**
 * Trait ContainerAware
 * Assists in satisfying the ContainerAwareInterface requirements
 * Exposes the management container and its public members.
 */
trait ContainerAware
{
    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @param ManagerInterface $manager
     *
     * @return void
     */
    public function bind(ManagerInterface $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return bool
     */
    public function isBound(): bool
    {
        return $this->manager instanceof ManagerInterface;
    }

    /**
     * Convenience method
     * Utilizes manager's implementation of hasShortcode
     * Limits search to this shortcode's context.
     *
     * @param string $content
     *
     * @throws RegisterException
     *
     * @return bool
     */
    public function hasShortcode(string $content): bool
    {
        if (!($this->isBound())) {
            throw RegisterException::missing($this->name);
        }

        return $this->manager->hasShortcode($content, $this->getContext());
    }

    /**
     * Convenience method
     * Utilizes manager's implementation of doShortcode
     * Limits search to this shortcode's context.
     *
     * @param string $content
     * @param bool   $deep
     *
     * @throws RegisterException
     *
     * @return string
     */
    public function doShortcode(string $content, bool $deep = false): string
    {
        if (!($this->isBound())) {
            throw RegisterException::missing($this->name);
        }

        return $this->manager->doShortcode($content, $this->getContext(), $deep);
    }

    /**
     * @return ManagerInterface
     */
    public function getManager(): ManagerInterface
    {
        return $this->manager;
    }

    /**
     * Utility method.
     *
     * @return array
     */
    private function getContext(): array
    {
        $context = [$this->name];
        if ($this instanceof AliasInterface) {
            $context = array_unique(array_merge($context, $this->getAlias()));
        }

        return $context;
    }
}
