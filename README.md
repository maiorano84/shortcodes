# WP Shortcodes
Implementation of Wordpress' Shortcode functionality as a standalone package. Content from editors, databases, etc. can be scanned by the Shortcode Manager and the contents replaced by a callback using the following format:

[tag attribute=value]Content[/tag]

## Requirements
WP Shortcodes requires PHP 5.4 or greater.

## Composer
This package may be installed as a Composer dependency by entering the following in your composer.json:

```
"repositories": [
    {
      "url": "https://github.com/maiorano84/WPShortcodes",
      "type": "vcs"
    }
  ],
  "require": {
    "maiorano84/wp-shortcodes": "dev-master"
  }
```

## Usage

This package comes with everything you need for defining your own custom shortcodes and their respective callbacks. To define a custom Shortcode programatically, you can use the SimpleShortcode class:

```php
//Instantiate a Shortcode Manager
$manager = new Maiorano\WPShortcodes\Manager\ShortcodeManager;
//Create your shortcode
$foo = new Maiorano\WPShortcodes\Shortcode\SimpleShortcode('foo', null, function(){
    return 'bar';
});
$manager->register($foo);
```
If you would like to create your own custom class, you can do the following:

```php
use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;
use Maiorano\WPShortcodes\Shortcode\AttributeInterface;
use Maiorano\WPShortcodes\Shortcode\ShortcodeTrait;
use Maiorano\WPShortcodes\Shortcode\AttributeTrait;

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