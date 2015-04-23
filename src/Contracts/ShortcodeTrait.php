<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ManagerInterface;

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
     * @var callable
     */
    protected $callback;

    /**
     * @return string
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::handle()
     */
    public function handle($content = null, array $atts = [])
    {
        if (!is_null($this->callback)) {
            $c = $this->callback;
            $callback = $c->bindTo($this, $this);

            return $callback($content, $atts);
        }

        return (string)$content;
    }

    /**
     * @param ManagerInterface $manager
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::bind()
     */
    public function bind(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }
}