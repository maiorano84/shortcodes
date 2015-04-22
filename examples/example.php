<?php
require '../vendor/autoload.php';

use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library;

$manager = new ShortcodeManager(array(
    'ipsum' => new Library\Ipsum,
    'age' => new Library\Age
));
$dateShortcode = new Library\SimpleShortcode('date', null, function () {
    return date('l \t\h\e jS \o\f F, Y');
});

//Supports method chaining on register/deregister
echo $manager->register($dateShortcode)->doShortcode('Today is [date]') . '<br><br>';
//Supports specifying shortcodes individually...
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. "[date]" is not rendered', 'age') . '<br><br>';
//... or in groups
echo $manager->doShortcode('I am [age units=minutes]September 19th, 1984[/age] old. Today is [date]', 'age|date') . '<br><br>';
//If you don't want a particular shortcode rendered in a particular block of text, you can escape it
echo $manager->doShortcode('I am [[age units=minutes]September 19th, 1984[/age]] old. Today is [[date]]') . '<br><br>';