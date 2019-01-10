# Shortcodes
Implementation of Wordpress' Shortcode syntax as a standalone package. Content from editors, databases, etc. can be scanned by the Shortcode Manager and the contents replaced by a custom callback.

----

[![Build Status](https://travis-ci.org/maiorano84/shortcodes.svg?branch=master)](https://travis-ci.org/maiorano84/shortcodes)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maiorano84/shortcodes/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maiorano84/shortcodes/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/maiorano84/shortcodes/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/maiorano84/shortcodes/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/maiorano84/shortcodes/v/stable)](https://packagist.org/packages/maiorano84/shortcodes)
[![Latest Unstable Version](https://poser.pugx.org/maiorano84/shortcodes/v/unstable)](https://packagist.org/packages/maiorano84/shortcodes)
[![Total Downloads](https://poser.pugx.org/maiorano84/shortcodes/downloads)](https://packagist.org/packages/maiorano84/shortcodes)
[![License](https://poser.pugx.org/maiorano84/shortcodes/license)](https://packagist.org/packages/maiorano84/shortcodes)
[![SymfonyInsight](https://insight.symfony.com/projects/f1157d4b-fc5f-4b9f-be34-7814abb4edb5/mini.svg)](https://insight.symfony.com/projects/f1157d4b-fc5f-4b9f-be34-7814abb4edb5)

## Requirements
Shortcodes requires PHP 7.1 or greater.

## Composer
This package may be installed as a Composer dependency by entering the following in your composer.json:

```
"require": {
  "maiorano84/shortcodes": "@dev"
}
```

or running the following command:

`composer require maiorano84/shortcodes`

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

This package comes with everything you need to get started in defining your own custom shortcodes and their respective callbacks. To define a custom Shortcode programatically, you can use the SimpleShortcode class:

```php
use Maiorano\Shortcodes\Manager;
use Maiorano\Shortcodes\Library;

//Instantiate a Shortcode Manager
$manager = new Manager\ShortcodeManager;
//Create your shortcode
$example = new Library\SimpleShortcode('example', ['foo'=>'bar'], function($content=null, array $atts=[]){
    return $content.$atts['foo'];
});
$manager->register($example)->doShortcode('[example]Foo[/example]'); //Outputs: Foobar
```

Now, when you process a string that contains `[example]` it will be replaced by your custom callback.

## Nested Shortcodes

In most cases, the SimpleShortcode class will be sufficient in covering your app's needs. However, some cases may require a bit more configuration that the SimpleShortcode class doesn't assume out of the box.

One fairly common scenario you might run into is the need for Nested Shortcodes:

`'[foo][bar/][/foo]'`

In all of the examples thus far, none of them cover what would happen when you register two shortcodes where one wraps another.
Let's see what happens:

```php
$manager = new ShortcodeManager([
    'foo' => new Library\SimpleShortcode('foo', null, function ($content) {
        return 'foo' . $content;
    }),
    'bar' => new Library\SimpleShortcode('bar', null, function () {
        return 'bar';
    })
]);
echo $manager->doShortcode('[foo][bar/][/foo]'); //Outputs: foo[bar/]
```

Shortcodes - by their very nature - are meant to encapsulate *content*. That content is processed and returned to the manager for output.

The problem here is that [bar/] is matched and passed by the manager as *content*. Not Shortcode.

To handle this, there are several avenues we can take. Let's set up a manager that uses three shortcodes:

```php
$manager = new ShortcodeManager([
    'foo' => new Library\SimpleShortcode('foo', null, function ($content) {
        return 'foo' . $this->manager->doShortcode($content, 'bar');
    }),
    'bar' => new Library\SimpleShortcode('bar', null, function ($content) {
        return 'bar' . $content;
    }),
    'baz' => new Library\SimpleShortcode('baz', null, function () {
        return 'baz';
    })
]);
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
echo $manager->doShortcode('[foo][baz/][/foo]', 'foo', true); //Outputs: foo[baz/]
echo $manager->doShortcode('[foo][baz/][/foo]', 'foo|baz', true); //[baz] Permitted in this instance
```
There is an optional third parameter that you can set within `doShortcode`, which will allow the manager to decide which tags to render and run recursively until everything has been processed.

Even though we've defined that only [bar] is permitted to be processed in the [foo] callback, the Shortcode Manager can choose to override it any time.

**Option 3: Scorched Earth**

Don't care? Just want to handle all possible shortcodes and let the chips fall where they may?

Hold my beer:

```php
echo $manager->doShortcode('[foo][bar][baz/][/bar][/foo]', null, true); //Outputs: foobarbaz
```

## Aliasing

If you would like to create aliases for preexisting shortcodes, there are a number of ways to do this:

```php
$manager = new ShortcodeManager;
$bold = new SimpleShortcode('bold', null, function($content){
    return sprintf('<strong>%s</strong>', $content);
});
$manager->register($bold);
$manager->alias('bold', 'b');
```

Now when you use `[bold]` or `[b]`, they will both do the same thing.

**Be careful**: When registering aliases, deregistering the original shortcode will also deregister its associated aliases by default.

If you would like to deregister a particular shortcode but leave its aliases intact, you may optionally provide a second parameter that will tell the manager not to deregister aliases:

```php
$manager->deregister('bold', false);
```

Now, `[b]` will continue to be rendered, but `[bold]` will no longer be recognized.

## Shortcuts

There are a number of shortcuts provided for you out of the box. Registration can be as easy as this:

```php
$manager[] = $bold;
```

You may also alias a SimpleShortcode directly:

```php
$bold->alias('b');
```

In addition to the above, you may also run `doShortcode` on a SimpleShortcode directly. These two statements are identical:

```php
$manager->doShortcode('[bold]Bold[/bold][b]Bold[/b]', 'bold|b');
$bold->doShortcode('[bold]Bold[/bold][b]Bold[/b]');
```

And finally, deregistering a Shortcode and its aliases can be done like so:

```php
unset($manager['bold']);
```

## Other Notes

You might have noticed that the SimpleShortcode class can call to a protected member in order to achieve certain results. In every Shortcode that leverages the inherent `handle` method from the CallableTrait, the callback is bound to the scope of that particular instance. That means that even when protected and private members are declared, you still have access to those members within the scope of your callback.

Ultimately, the goal is to keep everything as flexible as possible in allowing you - the developer - to build your application in the way you want it.

Enjoy!
