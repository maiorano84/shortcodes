<?php
namespace Maiorano\WPShortcodes\Manager;

use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;

interface ShortcodeManagerInterface{
    public function register(ShortcodeInterface $shortcode);
    public function deregister($shortcode);
    public function getRegistered();
}