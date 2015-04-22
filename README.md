# Shortcodes
Implementation of Wordpress' Shortcode syntax as a standalone package. Content from editors, databases, etc. can be scanned by the Shortcode Manager and the contents replaced by a custom callback.

## Requirements
Shortcodes requires PHP 5.4 or greater.

## Composer
This package may be installed as a Composer dependency by entering the following in your composer.json:

```
"require": {
  "maiorano84/shortcodes": "1.0.0-beta"
}
```

## Usage

This package comes with everything you need for defining your own custom shortcodes and their respective callbacks. To define a custom Shortcode programatically, you can use the SimpleShortcode class:

```php
//Instantiate a Shortcode Manager
$manager = new Maiorano\Shortcodes\Manager\ShortcodeManager;
//Create your shortcode
$foo = new Maiorano\Shortcodes\Shortcode\SimpleShortcode('foo', null, function(){
    return 'bar';
});
$manager->register($foo);
```
If you would like to create your own custom class, you can do the following:

```php
use Maiorano\Shortcodes\Shortcode\ShortcodeInterface;
use Maiorano\Shortcodes\Shortcode\AttributeInterface;
use Maiorano\Shortcodes\Shortcode\ShortcodeTrait;
use Maiorano\Shortcodes\Shortcode\AttributeTrait;

class ExampleShortcode implements ShortcodeInterface, AttributeInterface{
    use ShortcodeTrait, AttributeTrait;
    protected $name = 'example';
    protected $attributes = array('foo'=>'bar');
    public function handle($content=null, array $atts=[])
    {
        return $content.$atts['foo'];
    }
}
```

Executing the above is as easy as doing this:

```php
$content = '[example foo=baz]Foo[/example]';
echo $manager->register(new ExampleShortcode)->doShortcode($content, 'example');
//Outputs: Foobaz
```