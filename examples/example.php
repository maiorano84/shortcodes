<?php
require '../vendor/autoload.php';

$manager = new Maiorano\Shortcodes\Manager\ShortcodeManager(array(
    'ipsum' => new Maiorano\Shortcodes\Examples\Ipsum,
    'age' => new Maiorano\Shortcodes\Examples\Age
));
$dateShortcode = new Maiorano\Shortcodes\Shortcode\SimpleShortcode('date', null, function () {
    return date('l \t\h\e jS \o\f F, Y');
});

//Supports method chaining on register/deregister
echo $manager->register($dateShortcode)->doShortcode('Today is [date]') . '<br><br>';
//Supports specifying shortcodes individually...
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. "[date]" is not rendered', 'age') . '<br><br>';
//... or in groups
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. Today is [date]', 'age|date') . '<br><br>';
//If you don't want a particular shortcode rendered, you can escape it
echo $manager->doShortcode('I am [[age units=minutes]September 19th, 1984[/age]] old. Today is [[date]]') . '<br><br>';