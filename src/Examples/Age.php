<?php
namespace Maiorano\WPShortcodes\Examples;

use \DateTime;
use Maiorano\WPShortcodes\Shortcode\ShortcodeTrait;
use Maiorano\WPShortcodes\Shortcode\AttributeTrait;
use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;

/**
 * Class Age
 * @package Maiorano\WPShortcodes\Examples
 */
class Age implements ShortcodeInterface{
    use ShortcodeTrait;
    use AttributeTrait;

    /**
     * @var string
     */
    protected $name = 'age';

    /**
     * @var array
     */
    protected $atts = ['units'=>'years'];

    /**
     * @param array $atts
     * @param string|null $content
     * @return string
     */
    public function handle(array $atts=[], $content=null)
    {
        if(!$content) return '';

        $now = new DateTime('now');
        $birthday = new DateTime($content);
        $diff = $now->diff($birthday);
        switch($atts['units']){
            case 'centuries': $v = $diff->y/100; break;
            case 'decades': $v = $diff->y/10; break;
            case 'months': $v = $diff->y*12+$diff->m; break;
            case 'days': $v = $diff->days+$diff->d; break;
            case 'hours': $v = ($diff->days*24)+$diff->h; break;
            case 'minutes': $v = ($diff->days*24*60)+$diff->i; break;
            case 'seconds': $v = ($diff->days*24*60*60)+$diff->s; break;
            case 'years': default: $v = $diff->y; break;
        }
        return sprintf('%d %s', $v, $atts['units']);
    }
}
