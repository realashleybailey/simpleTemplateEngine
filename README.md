Simple Template Engine
=====

A simple and lightweight PHP templating engine using pure PHP syntax.
Simple Template Engine adds on to PHP's templating capabilities by introducing blocks and template inheritance.

It's easy to learn and is useful for small websites or in conjunction with microframeworks.

Requires PHP version 5.3+


## Installation

Installation is super-easy via [Composer](https://getcomposer.org/):

```bash
$ composer require ashley/template-engine
```

or add it by hand to your `composer.json` file.


## Setup

To use the simple template engine, include composer, create an `Environment` object, and render away!
The Environment's `render()` function takes the path to a template, renders it and returns its contents as a string.

```php
require_once 'vendor/autoload.php';

$env = new Ashley\TemplateEngine\Environment('path/to/templates/directory');
echo $env->render('template.php');
```

You can also pass in an extension that will be appended to all template paths in Environment.

```php
$env = new Ashley\TemplateEngine\Environment('path/to/templates', '.php');

//will render index.php
echo $env->render('index');
```

You can pass variables to your template via an array:

```php
//index.php
echo $env->render('template.php', ['name'=>$value, 'fruit'=>'banana']);
```

You can then access the variable `$fruit` in your template, and its value will be apple.

```php
//template.php
My favourite fruit is <?php echo $fruit ?>.
```

The Environment can hold variables shared by all your templates such as helpers.  Set variables like this: 
```php
$env->helper = new Helper();
$env->colour = "green";
```

Now, in your template, you can use your `Helper` object and your `colour` variable.

```php
//inside template.php
My favourite colour is <?php echo $this->colour ?>.
<?php echo $this->helper->doSomething() ?>
```

Blocks
-----

Blocks are sections of layout that you can define and then use later.
You can define blocks by enclosing text in `$this->block('name here')` and `$this->endblock()`:

```php
<?php $this->block('title') ?>
Welcome to my site!
<?php $this->endblock() ?>
<?php
$this->block('title', 'Welcome to my site!'); //shortcut for small blocks
```
This will create a Block object that you can access later through `$this` by using their name.
For example, to output the block defined above: 

```php
<title><?php echo $this['title'] ?></title>
```

You can also use `if` structures to set a default block to use if a block is not defined: 

```php
<title>
<?php if(!$this['title']): ?>
Default Title
<?php else: echo $this['title']; endif; ?>
</title>
```

Output Escaping
-----
You can escape blocks of output easily: 

```php
echo $this['title']->escape();
// OR this shorthand notation
echo $this['title']->e();
```

The function `endblock` returns a Block object that you can output.
This is useful for escaping a Block immediately after ending it by calling `escape()`:

```php
<?php $this->block() ?>
<script>alert('I am dangerous code!');</script>
<?php echo $this->endblock()->escape() ?>

```

As you can see above, we didn't assign a name to our block because we output it right away, and have no need to save it.
If you don't assign a name to your block, it cannot be accessed later.

Filters
-----

You can pass a closure to `endblock` that you want to apply to the content.

```php
<?php $this->block() ?>
Hello, this is a block of text.
<?php echo $this->endblock(function($content) {
	return strtoupper($content);
});
?>
```

The above code converts a block's content to uppercase.

Template Inheritance
-----

Blocks are useful because we can define blocks in one template, then _extend_ another one and use it there!
This allows us to reuse a template such as a layout multiple times with different blocks.
Extending a template is done using the `extend` function:

```php
<?php $this->extend('layout.php'); ?>

<?php $this->block('title', 'My Awesome Page') ?>
<?php $this->block('scripts') ?>
<script src="jquery.js"></script>
<?php $this->endblock() ?>

This is my content.
```

When you extend a parent template, any non-block code in the child will become a special block named content in the parent.
In the above code, we defined a title block and some content.  Now we can use it in our extended layout.
In layout.php, we can output our content and title with `$this['content']` and `$this['title']`.

```php
<!-- my layout -->
<html>
	<head>
		<title><?=$this['title'] ?></title>
		<?=$this['scripts'] ?>
	</head>
	<body>
		<?=$this['content'] ?>
	</body>
</html>
```

Please be careful not to name a block _content_ unless you are intentionally defining a content block.
If you define a content block, it will be prepended to the non-block content in the layout.

**A template that extends a template that extends a template**: If the parent template is extending another template, then any non-block content in the parent will be appended to the content block of their child.  This new content block will become the content block of the grandparent.

Templates
-----

Templates can also be created without having to render from file and their blocks can be defined within PHP.
You can create a new template object through the `template()` function of Environment.

```php
$template = $env->template();
$template->extend('layout.php');
$template['content'] = "hello";
echo $template->render();
```

Remember that a Template object created this way must have a layout otherwise nothing will be rendered.

That's all!
-----

Now you have everything you need to create a great website!
