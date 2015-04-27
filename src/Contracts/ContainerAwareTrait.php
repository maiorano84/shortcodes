<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Trait ContainerAwareTrait
 * @package Maiorano\Shortcodes\Contracts
 */
trait ContainerAwareTrait
{

    /**
     * @param ManagerInterface $manager
     * @see Maiorano\Shortcodes\Contracts\ContainerAwareInterface::bind()
     */
    public function bind(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return bool
     * @see Maiorano\Shortcodes\Contracts\ContainerAwareInterface::isBound()
     */
    public function isBound()
    {
        return $this->manager instanceof ManagerInterface;
    }

    /**
     * @param string $content
     * @param bool $deep
     * @return string
     * @throws RegisterException
     */
    public function hasShortcode($content, $deep = false)
    {
        if (!($this->isBound())) {
            $e = sprintf(RegisterException::MISSING, $this->name);
            throw new RegisterException($e);
        }

        return $this->manager->hasShortcode($content, $this->getContext(), $deep);
    }

    /**
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

    /**
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
