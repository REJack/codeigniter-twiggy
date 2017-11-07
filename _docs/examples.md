# CodeIgniter-Twiggy
## Examples & Best Practices

There are a lot of ways you can use Twiggy and nobody is going to beat you with a bat if you do it your own way. On the other hand, knowing how to efficiently use this library to your advantage can't hurt, right? Okay then, here we go!

### Data variables
Twiggy distinguishes two types of data variables: standard (local) and global. The first one is pretty obvious -- these variables are only available inside the loaded template. Global variables, on the other hand, are accessible from anywhere within Twig template files. They're extremely useful when you need to have access to some data no matter which template file you load, for example: authenticated user information such as username, avatar etc. Standard variables are good for page-specific data. For example, you want to iterate through an array of tracks for a specific music album. So let's take a look at how to set and access data variables.

#### Synopsis
```php
$this->twiggy->set($key, $value, $global = NULL);
```

#### Standard variables
```php
$this->twiggy->set('tracks', array('Afterlife', 'The Original', 'The War Inside'));
```

#### Global variables
```php
$this->twiggy->set('user', array('username' => 'edmundas', 'email' => 'my.name@email.com'), TRUE);
```
---
You can also set multiple variables at once.
```php
$data = array();

// Presumably you'll want to get these from the database through the model
$data['albums'] = array('Nothing Is Sound', 'Oh! Gravity.', 'Vice Verses');
$data['tracks'] = array('Afterlife', 'The Original', 'The War Inside');

$this->twiggy->set($data);
```
In the example above those would be standard variables. In order to set multiple `global` variables, just leave the second parameter empty (NULL) and set the third parameter as TRUE.
```php
$data = array();

// Presumably you'll want to get these from the database through the model
$data['albums'] = array('Nothing Is Sound', 'Oh! Gravity.', 'Vice Verses');
$data['tracks'] = array('Afterlife', 'The Original', 'The War Inside');

// For some weird reason these need to be global
$this->twiggy->set($data, NULL, TRUE);
```

#### Accessing data
We'll not cover how to use variables inside templates as everything is well documented at the [official twig website](https://twig.symfony.com/). However, sometimes you will need to get the data inside the controller, model or even standard CodeIgniter view files.
```php
$albums = $this->twiggy->albums
```
Here we see a bit of PHP 5 magic with `__get()` method to get data variables.

#### Unsetting data
```php
$this->twiggy->unset_data('albums');
```

### Handling output
You can echo the output by calling `$this->twiggy->display()` at the end of your controller. However, there's another method which does not print the ouput but rather returns it.
```php
// Render (compile) the output but don't display it
$output = $this->twiggy->render();
```
This gives you the possibility of sending the output to the `Output` class (one of CodeIgniter's system classes).
```php
// Render (compile) the output but don't display it
$output = $this->twiggy->render();

// Set output
$this->output->set_output($output);
```

### Method chaining
Instead of calling each method on a new line, why don't we take advantage of PHP 5 and use method chaining?
```php
$this->twiggy->set('username', 'edmundas')->template('account')->display();
```
This is the same as writting:
```php
$this->twiggy->set('username', 'edmundas');
$this->twiggy->template('account');
$this->twiggy->display();
```

### Functions & Filters
By default, functions are not accessible from the template files in Twig. So, for example, calling phpversion() wouldn't work:
```html
{% extends _layout %}

{% block content %}

    Hello, I'm a template. I want you to meet PHP {{ phpversion() }}.

{% endblock %}
```
This provides you with a delicate way of dealing with template files where you want to limit exposure of potentially unsafe function calls. Therefore, in order to use functions you must register them within the Twig environment.
```php
$this->twiggy->register_function('phpversion');
```

#### Filters
Filters are essentially just plain ol' functions, except Twig expects these functions to do something with given data and return it. For example, you want to have a filter that takes a string as a parameter and returns the string with a prefix attached to it.
```php
function prefix_it($input, $prefix = 'prefix_')
{
    return $prefix . $input;
}
```
As with functions, filters must be registered within the Twig environment.
```php 
$this->twiggy->register_filter('prefix_it');
```
Finally, use the filter in the template.
```html
The table name is {{ "users"|prefix_it('cms_') }}.
```
In the output you should get:
```
The table name is cms_users.
```

#### Auto-register functions & filters
Registering functions and filters can get somewhat tedious when you have a lot of them and they get used frequently. Luckily, Twiggy has it covered. In the configuration file you should see two option keys: `register_functions` and `register_filters`. Yup, this is the place to put your oftenly used functions and filters!
```php
$config['twiggy']['register_functions'] = array
(
    // Some functions from the url_helper.php
    'site_url', 'base_url', 'uri_string', 'anchor', 'url_title'
);

$config['twiggy']['register_filters'] = array
(
    // Functions (filters) from the inflector_helper.php
    'singular', 'plural', 'camelize', 'underscore', 'humanize'
);
```
Of course, keep in mind that these functions must be defined (meaning that if you use functions from helpers, make sure these helpers are loaded).

### Accessing Twig instance
Twiggy exposes only the essential (and most frequently used) Twig functionality. However, there may be times when you will need to do something tricky. Fear not, you have access to the twig instance!
```php
print_r($this->twiggy->twig);
```
Twig allows you to not only register functions but also register them with different names. This is not possible in the current version of Twiggy through `$this->twiggy->register_function()`. But since we have access to the twig instance, we can call a method that will do what we want.
```php
$this->twiggy->twig->addFunction('my_phpversion', new Twig_Function_Function('phpversion'));
```
In the template files we'll be able to call our function as such:
```
Turns out I can have my own function name! Long live PHP {{ my_phpversion() }}!
```
We won't go through advanced Twig features here but you can always check the [official documentation](https://twig.symfony.com/doc/2.x/) for reference.
__WARNING:__ twig instance is accessed with the help of `__get()` magic PHP method. But, as you know, it is also used for accessing variables. Therefore, do not set a template variable with the name 'twig'. Doing so won't overwrite the twig instance but you will not be able to access it in your PHP code.

### Dealing with the title tag
While you can have, let's say, a global variable to store the value of the title tag, it might become tedious setting that value for each page while keeping track of which section of the website you are in (in regards to previous/parent page).
Luckily, Twiggy has a few methods to make your life easier.

#### Before digging deeper
There is no voodoo mojo stuff here. You are not forced to use this extra functionality. Essentially all that it does is it sets a global variable named `title` based on how you use these additional methods. This variable, like any other, can be used inside twig templates.
```html
<title>{{ title }}</title>
```

#### Set the title
```php
$this->twiggy->title('Twiggy is awesome');
```
With this method you can set the __default__ title in, for example, `MY_Controller.php`.

#### Prepend and append data to the title
Depending on your SEO strategy, convenience or other factors, you can prepend or append data to the existing title string.
```php
$this->twiggy->title()->prepend('Home');
```
This will produce:
```
Home | Twiggy is awesome
```
while
```php
$this->twiggy->title()->append('Home');
```
will produce:
```
Twiggy is awesome | Home
```
Note that when you want to prepend or append data to the __existing__ title string, leave the title method empty (without params). Also, you may not like the title separator. It can be changed with the `set_title_separator()` method.
```php
$this->twiggy->title('My personal blog')
             ->prepend('Posts')
             ->set_title_separator(' - ')
             ->prepend('First blog entry');
```
The code above will produce:
```
First blog entry - Posts | My personal blog
```
As you can see, method chaining works here as well.
One last trick to remember is that `title()`, `prepend()` and `append()` methods accept multiple parameters.
```php
// By default passing in multilple params will invoke append()
$this->twiggy->title('Control Panel', 'Login');
```
Output:
```
Control Panel | Login
```
The code above is equivalent to this:
```php
$this->twiggy->title('Control Panel')->append('Login');
```

### Meta data (meta tags)
Much like with the title tag, Twiggy helps you by eliminating the need to bother with meta tags "the hard way".

#### Synopsis
```php
$this->twiggy->meta($name, $value, $attribute = 'name');
```
You will most likely want to set meta keywords and description. We won't get into whether having these tags in your code matter anymore in regards to search engines, that's not our goal!
```php
$this->twiggy->meta('keywords', 'twiggy, twig, template, layout, codeigniter');
$this->twiggy->meta('description', 'Twiggy is an implementation of Twig template engine for CI');
```
Then in your template file (probably the layout file) you can use this global variable named `meta`.
```html
<html>
  <head>
    {{ meta }}
  ...
```
Twiggy will automatically compile the data into HTML code when `render()` or `display()` method is called.
```html
<meta name="keywords" value="twiggy, twig, template, layout, codeigniter">
<meta name="description" value="Twiggy is an implementation of Twig template engine for CI">
```

#### Getting metadata in PHP code
As the code suggests, you can either get the raw data (an array) or compiled HTML code.
```php
$this->twiggy->get_meta('keywords');
```
The code above will return "keywords" array.
```php
$this->twiggy->get_meta('keywords', TRUE);
```
Here you will get a string (HTML code) instead.
```php
$this->twiggy->get_meta(NULL, TRUE);
```
In this case you will get __ALL__ meta data compiled into HTML code since the first parameter is NULL (empty).

#### Unset metadata
Naturally you can unset meta data with `unset_meta()`.
```php
// unset only the keywords meta tag
$this->twiggy->unset_meta('keywords');

// unset all meta tags
$this->twiggy->unset_meta();

// unset keywords and description
$this->twiggy->unset_meta('keywords', 'description');
```

### Final Notes
Make sure to check the configuration file to see which options you can change to suit your needs.