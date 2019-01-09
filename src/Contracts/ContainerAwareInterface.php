<?php

namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;

/**
 * Interface ContainerAwareInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface ContainerAwareInterface extends ShortcodeInterface
{
    /**
     * Bind the management container to a Shortcode
     * Exposes a manager's public methods
     * @param ManagerInterface $manager
     * @return void
     */
    public function bind(ManagerInterface $manager): void;

    /**
     * Determine if the management container exists as a property
     * @return bool
     */
    public function isBound(): bool;

    /**
     * @return ManagerInterface
     */
    public function getManager(): ManagerInterface;
}
