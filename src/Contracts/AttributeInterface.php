<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface AttributeInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface AttributeInterface
{

    /**
     * Get an array of recognized attributes and their defaults
     * @return array
     */
    public function getAttributes();
}