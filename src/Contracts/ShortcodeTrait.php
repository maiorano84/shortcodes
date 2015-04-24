<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Assists in satisfying the ShortcodeInterface requirements
 * @package Maiorano\Shortcodes\Contracts
 */
trait ShortcodeTrait
{

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @return string
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ManagerInterface $manager
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::bind()
     */
    public function bind(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $content
     * @param bool $deep
     * @return string
     * @throws RegisterException
     */
    public function doShortcode($content, $deep = false)
    {
        if (!($this->manager instanceof ManagerInterface)) {
            $e = sprintf(RegisterException::MISSING, $this->name);
            throw new RegisterException($e);
        }

        $context = $this->name;
        if ($this instanceof AliasInterface) {
            $context = $this->alias;
            $context[] = $this->name;
        }

        return $this->manager->doShortcode($content, $context, $deep);
    }
}