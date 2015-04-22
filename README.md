# Shortcodes
Implementation of Wordpress' Shortcode syntax as a standalone package. Content from editors, databases, etc. can be scanned by the Shortcode Manager and the contents replaced by a custom callback.

## Requirements
Shortcodes requires PHP 5.4 or greater.

## Composer
This package may be installed as a Composer dependency by entering the following in your composer.json:

```
"require": {
  "maiorano84/shortcodes": "~1.0"
}
```
## What are "Shortcodes"?

Shortcodes are a common format used to provide macro support in various CMSs, editors, and libraries. Perhaps the most famous example is the [Wordpress Shortcode API](https://codex.wordpress.org/Shortcode_API), which provides developers an avenue by which they can enhance their theme or plugin's ease of use.

The anatomy of a shortcode is as follows:

```
[tag] - No content, no attributes
[tag]Content[/tag] - Tag with content
[tag atribute=value] - Tag with attributes
[tag atribute=value]Content[/tag] - Tag with content and attributes
```

There are many formats that shortcodes can follow, but ultimately the idea is that when a shortcode tag is matched, its contents are replaced by a server-side callback.

## Usage

This package comes with everything you need for defining your own custom shortcodes and their respective callbacks. To define a custom Shortcode programatically, you can use the SimpleShortcode class:

```php
use Maiorano\Shortcodes\Manager;
use Maiorano\Shortcodes\Library;

//Instantiate a Shortcode Manager
$manager = new Manager\ShortcodeManager;
//Create your shortcode
$example = new Library\SimpleShortcode('example', array('foo'=>'bar'), function($content=null, array $atts=[]){
    return $content.$atts['foo'];
});
$manager->register($example)->doShortcode('[example]Foo[/example]'); //Outputs: Foobar
```

Now, when you process a string that contains `[example]` it will be replaced by your custom callback.

If you would like to create your own custom class, you can do the following:

```php
use Maiorano\Shortcodes\Contracts;

class ExampleShortcode implements Contracts\ShortcodeInterface, Contracts\AttributeInterface
{
    use Contracts\ShortcodeTrait, Contracts\AttributeTrait;
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
echo $manager->register(new ExampleShortcode)->doShortcode('[example foo=baz]Foo[/example]'); //Outputs: Foobaz
```