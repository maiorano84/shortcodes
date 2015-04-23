<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;

/**
 * Interface ShortcodeInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface ShortcodeInterface
{

    /**
     * All Shortcodes must return a name as a string
     * This is used by the management container for registration
     * @return string
     */
    public function getName();

    /**
     * Executed upon match and determines output of Shortcodes
     * @return string
     */
    public function handle();

    /**
     * Bind the management container to a Shortcode
     * This helps support nested shortcodes
     * @param ManagerInterface $manager
     * @return void
     */
    public function bind(ManagerInterface $manager);
}