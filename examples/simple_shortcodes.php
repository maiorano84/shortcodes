<?php
require '../bootstrap/autoload.php';

use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library\SimpleShortcode;

/*
 * Managers may be instantiated with an array of Shortcodes pre-registered into its library
 */
$manager = new ShortcodeManager([
    'date' => new SimpleShortcode('date', null, function () {
        return date('l \t\h\e jS \o\f F, Y');
    })
]);

/*
 * You can chain alias/register/deregister methods
 * This aliases the above [date] tag with [d]
 */
echo $manager->alias('date', 'd')->doShortcode('Today is [d]').'<br><br>';

/*
 * You may choose which shortcodes you would like to render
 * */
echo $manager->doShortcode('Today is [date], not "[d]"', 'date').'<br><br>';

/*
 * Shortcodes and their aliases can be registered at any time
 * */
echo $manager->register(new SimpleShortcode('mail', ['address'=>''], function($content, $atts){
    return sprintf('<a href="%s">%1$s</a>%s', $atts['address'] ?: '#', $content);
}))->alias('mail', 'm')->doShortcode('[m address=test@test.com]Test[/m]').'<br><br>';

/*
 * Nested shortcode can be processed by the Manager
 * You can also decide which tags are available for rendering
 * */
echo $manager->register(new SimpleShortcode('nest', null, function ($content) {
    return $this->manager->doShortcode($content, 'mail');
}))->doShortcode('[nest]My email is [mail address=test@test.com]Test[/mail], but "[date]" doesn\'t work[/nest]').'<br><br>';

/*
 * You may also tell the original calling manager to override permissions
 * A third parameter of 'true' will attempt to use the calling manager's tag declarations
 * */
echo $manager->doShortcode('[nest]My email is [mail address=test@test.com], and the date is [d/][/mail][/nest]', 'nest|d', true).'<br><br>';

/*
 * What have we enabled so far?
 * */
echo '<pre>';var_dump($manager->getRegistered());echo '</pre><br><br>';

/*
 * Let's get rid of 'm' and use it for something else
 * */
echo $manager->deregister('m')->register(new SimpleShortcode('m', null, function(){
    return 'M is pretty fantastic';
}))->doShortcode('My opinion on the letter "M": [m]').'<br><br>';