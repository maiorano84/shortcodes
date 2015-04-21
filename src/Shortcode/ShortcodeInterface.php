<?php
namespace Maiorano\WPShortcodes\Shortcode;

interface ShortcodeInterface{
    public function getName();
    public function handle(array $atts=array(), $content=null);
}