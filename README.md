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

## Advanced Usage

In most cases, the SimpleShortcode class will be sufficient in covering your app's needs. However, some cases may require quite a bit more functionality that the SimpleShortcode class just hasn't thought of.

One fairly common scenario you might run into is the need for Nested Shortcodes:

`'[foo][bar/][/foo]'`

In all of the examples thus far, none of them cover what would happen when you register two shortcodes where one wraps another.
Let's see what happens:

```php
$manager = new ShortcodeManager(array(
    'foo' => new Library\SimpleShortcode('foo', null, function ($content) {
        return 'foo' . $content;
    }),
    'bar' => new Library\SimpleShortcode('bar', null, function () {
        return 'bar';
    })
));
echo $manager->doShortcode('[foo][bar/][/foo]'); //Outputs: foo[bar/]
```

Shortcodes - by their very nature - are meant to encapsulate *content*. That content is processed and returned to the manager for output.

The problem here is that [bar/] is matched and passed by the manager as *content*. Not Shortcode.

To handle this, there are several avenues we can take. Let's set up a manager that uses three shortcodes:

```php
$manager = new ShortcodeManager(array(
    'foo' => new Library\SimpleShortcode('foo', null, function ($content) {
        return 'foo' . $this->manager->doShortcode($content, 'bar');
    }),
    'bar' => new Library\SimpleShortcode('bar', null, function ($content) {
        return 'bar' . $content;
    }),
    'baz' => new Library\SimpleShortcode('baz', null, function () {
        return 'baz';
    })
));
```

[foo] - Render shortcode as text with content appended that permits [bar] to be processed
[bar] - Render shortcode as text with content appended
[baz] - Render shortcode as text

**Option 1: Selective**
```php
echo $manager->doShortcode('[foo][bar/][/foo]'); //Outputs: foobar
echo $manager->doShortcode('[foo][baz/][/foo]'); //Outputs: foo[baz/]
```

The benefit to this approach is that YOU can decide what gets processed and what doesn't. If there's a possibility that your Shortcode contains content that should be processed by the manager that called it, then your best bet would be to return the content wrapped in another call to `doShortcode`.

**Option 2: Permissive**

```php
echo $manager->doShortcode('[foo][baz/][/foo]', 'foo|baz', true); //[baz] Permitted in this instance
```
There is an optional third parameter that you can set within `doShortcode`, which will trigger the manager to run recursively over all defined tags until everything has been processed.

Even though we've defined that only [bar] is permitted to be processed in the [foo] callback, the Shortcode Manager can choose to override it any time.

**Option 3: Scorched Earth**

Don't care? Just want to handle all possible shortcodes and let the chips fall where they may?

Hold my beer:

```php
echo $manager->doShortcode('[foo][bar][baz/][/bar][/foo]', null, true); //Outputs: foobarbaz
```

## Other Notes

You might have noticed that the SimpleShortcode class is calling to a protected member in order to achieve certain results. In every Shortcode that leverages the inherent `handle` method from the ShortcodeTrait, the callback is bound to the scope of that particular instance. That means that even when protected and private members are declared, you still have access to those members within the scope of your callback.

With that in mind, every custom Shortcode that you create as a separate class will require a `bind` method that accepts the calling Manager container whether or not you actually require it. This is also predefined for you in the ShortcodeTrait provided.

Ultimately, the goal is to keep everything as flexible as possible in allowing you - the developer - to build your application in the way you want it.

Enjoy!