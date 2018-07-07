<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\Traits\Shortcode;
use Maiorano\Shortcodes\Contracts\Traits\Alias;

/**
 * Generate Lorem Ipsum
 * Usage: [loremipsum] or [ipsum]
 * @package Maiorano\Shortcodes\Library
 */
class Ipsum implements ShortcodeInterface, AliasInterface
{
    use Shortcode, Alias;

    /**
     * @var string
     */
    protected $name = 'loremipsum';

    /**
     * @var array
     */
    protected $alias = ['ipsum'];

    /**
     * @var string
     */
    private $ipsum = '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent laoreet eu nulla sit amet porttitor. Sed accumsan nulla est,
        sit amet lobortis nunc convallis pretium. Phasellus aliquet euismod lacus, non maximus odio pulvinar quis. Nulla eu lorem malesuada,
        aliquam risus sit amet, interdum ligula. Vivamus sollicitudin bibendum accumsan. Maecenas sit amet ornare turpis, quis porttitor quam.
        Mauris turpis purus, bibendum in diam in, rutrum accumsan arcu.';

    /**
     * @return string
     */
    public function handle()
    {
        return $this->ipsum;
    }

    /**
     * @return string
     */
    public function getIpsum()
    {
        return $this->ipsum;
    }
}
