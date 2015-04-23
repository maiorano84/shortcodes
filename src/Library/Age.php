<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeTrait;
use Maiorano\Shortcodes\Contracts\AttributeTrait;
use \DateTime;

/**
 * Calculates the age of something
 * Usage: [age units=years]September 19th 1984[/age]
 * @package Maiorano\Shortcodes\Library
 */
class Age implements ShortcodeInterface, AttributeInterface
{
    use ShortcodeTrait, AttributeTrait;

    /**
     * @var string
     */
    protected $name = 'age';

    /**
     * @var array
     */
    protected $attributes = ['units' => 'years'];

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     */
    public function handle($content = null, array $atts = [])
    {
        if (!$content) {
            return '';
        }

        $now = new DateTime('now');
        $birthday = new DateTime($content);
        $diff = $now->diff($birthday);
        switch ($atts['units']) {
            case 'centuries':
                $v = $diff->y / 100;
                break;
            case 'decades':
                $v = $diff->y / 10;
                break;
            case 'months':
                $v = $diff->y * 12 + $diff->m;
                break;
            case 'days':
                $v = $diff->days + $diff->d;
                break;
            case 'hours':
                $v = ($diff->days * 24) + $diff->h;
                break;
            case 'minutes':
                $v = ($diff->days * 24 * 60) + $diff->i;
                break;
            case 'seconds':
                $v = ($diff->days * 24 * 60 * 60) + $diff->s;
                break;
            case 'years':
            default:
                $v = $diff->y;
                break;
        }

        return sprintf('%d %s', $v, $atts['units']);
    }
}
