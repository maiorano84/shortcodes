<?php
namespace Maiorano\WPShortcodes\Examples;

//use \DateTime;

use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;

class Age implements ShortcodeInterface{

    public function getName()
    {
        return 'age';
    }
    public function handle()
    {

    }

    /*public static function getAge($results){
        if(!$results['content']){
            return $results;
        }
        
        $now = new DateTime('now');
        $birthday = new DateTime($results['content']);
        $diff = $now->diff($birthday);
        switch($results['atts']['units']){
            case 'centuries': $v = $diff->y/100; break;
            case 'decades': $v = $diff->y/10; break;
            case 'months': $v = $diff->y*12+$diff->m; break;
            case 'days': $v = $diff->days+$diff->d; break;
            case 'hours': $v = ($diff->days*24)+$diff->h; break;
            case 'minutes': $v = ($diff->days*24*60)+$diff->i; break;
            case 'seconds': $v = ($diff->days*24*60*60)+$diff->s; break;
            case 'years': default: $v = $diff->y; break;
        }
        $results['age'] = $v;
        return $results;
    }
    public static function check($html){
        return $html;
    }*/
}
