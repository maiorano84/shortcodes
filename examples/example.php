<?php
require '../vendor/autoload.php';

$manager = new Maiorano\WPShortcodes\Manager\ShortcodeManager(array(
    'ipsum' => new Maiorano\WPShortcodes\Examples\Ipsum,
    'age' => new Maiorano\WPShortcodes\Examples\Age
));
$dateShortcode = new Maiorano\WPShortcodes\Shortcode\SimpleShortcode('date', null, function () {
    return date('l \t\h\e jS \o\f F, Y');
});

//Supports method chaining on register/deregister
echo $manager->register($dateShortcode)->doShortcode('Today is [date]') . '<br><br>';
//Supports specifying shortcodes individually...
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. "[date]" is not rendered', 'age') . '<br><br>';
//... or in groups
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. Today is [date]', 'age|date') . '<br><br>';