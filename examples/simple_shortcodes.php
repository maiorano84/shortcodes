<?php
require '../vendor/autoload.php';

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
    return sprintf('<a href="%s">%1$s</a>%s', $atts['address'] ? 'mailto:'.$atts['address'] : '#', $content);
}))->alias('mail', 'm')->doShortcode('[m address=test@test.com]Test[/m]').'<br><br>';

/*
 * Nested shortcode can be processed by the Manager
 * You can also decide which tags are available for rendering
 * */
echo $manager->register(new SimpleShortcode('nest', null, function ($content) {
    return $this->manager->doShortcode($content, 'mail');
}))->doShortcode('[nest]My email is [mail address=test@test.com], but "[date]" doesn\'t work[/nest]').'<br><br>';

/*
 * You may also tell the original calling manager to override permissions
 * A third parameter of 'true' will attempt to use the calling manager's tag declarations
 * */
echo $manager->doShortcode('[nest]My email is [mail address=test@test.com], and the date is [d/][/mail][/nest]', 'nest|d', true).'<br><br>';

/*
 * Let's get rid of 'm' and use it for something else
 * */
echo $manager->deregister('m')->register(new SimpleShortcode('m', null, function(){
    return 'M is pretty fantastic';
}))->doShortcode('My opinion on the letter "M": [m]').'<br><br>';

/*
 * Let's go even further
 * Let's deregister the original [date] shortcode, but keep its alias
 * The second parameter allows us to prevent deregistration of a given shortcode's aliases
 * */
echo $manager->deregister('date', false)->doShortcode('Today is [d], not "[date]"').'<br><br>';

/*
 * There are also a few shorthand methods!
 * Registration and Deregistration may be performed using the manager's ArrayAccess Implementation
 * There is also a doShortcode method for Shortcode Classes that will allow you to run the manager against only that particular shortcode and its aliases
 * Aliasing is also availble to SimpleShortcode, and Shortcodes that implement AliasInterface
 * */
$bold = new SimpleShortcode('bold', null, function($content){
    return sprintf('<strong>%s</strong>', $content);
});
$manager[] = $bold; //Shorthand register
$bold->alias('b'); //Register an alias directly on a shortcode
echo $bold->doShortcode('[nest][bold]Bold Text[/bold] [b]More Bold Text[/b][/nest]').'<br><br>'; //Run doShortocde directly on a Shortcode and its aliases
unset($manager['bold']); //Deregister a shortcode and all of its aliases
echo $bold->doShortcode('[nest][bold]Not so bold text[/bold], [b]or this[/b][/nest]').'<br><br>';
