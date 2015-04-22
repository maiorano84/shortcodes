<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ShortcodeManagerInterface;

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
     * Executed upon match and determines output of Contracts
     * @return string
     */
    public function handle();

    /**
     * @param ShortcodeManagerInterface $manager
     * @return void
     */
    public function bind(ShortcodeManagerInterface $manager);
}