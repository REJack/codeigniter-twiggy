# CodeIgniter-Twiggy
## Configuration

### Title

#### title_separator
__Title separator__
Lets you specify the separator used in separating sections of the title variable.
> **[info] Default Value**
> 
> `' | '`

#### title_adding_method
__Title adding method__
Lets you specify the adding method used in title()
Options: `append`, `prepend`
> **[info] Default Value**
> 
> `append`

### Functions, Filters & Globals

#### register_functions
__Auto-reigster functions__
Here you can list all the functions that you want Twiggy to automatically register them for you.
NOTE: only registered functions can be used in Twig templates.
> **[info] Default Value**
> 
> `array()`

#### use_user_defined_functions
__Auto-register all user defined functions__
If set to `TRUE` make sure you don't have camelCase functions.
Example: having like function `setId()` will result in changing its usage inside twig as `{{ setid() }}` because PHP's `get_defined_functions()` function returns any function name lower-cased.
> **[info] Default Value**
> 
> `FALSE`

#### register_safe_functions
__Auto-reigster safe functions__
Here you can list all the functions that you want Twiggy to automatically register them for you and automatic escapes the output.
NOTE: only registered functions can be used in Twig templates.
NOTE: More details in twig documentation. [Twig 1](https://twig.symfony.com/doc/1.x/advanced.html#automatic-escaping) [Twig 2](https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping)
> **[info] Default Value**
> 
> `array()`

#### register_filters
__Auto-reigster filters__
Much like with functions, list filters that you want Twiggy to automatically register them for you.
NOTE: only registered filters can be used in Twig templates. Also, keep in mind that a filter is nothing more than just a regular function that acceps a string (value) as a parameter and outputs a modified/new string.
> **[info] Default Value**
> 
> `array()`

#### register_safe_filters
__Auto-reigster safe filters__
Much like with functions, list filters that you want Twiggy to automatically register them for you and automatic escapes the output.
NOTE: only registered filters can be used in Twig templates. Also, keep in mind that a filter is nothing more than just a regular function that acceps a string (value) as a parameter and outputs a modified/new string.
NOTE: More details in twig documentation. [Twig 1](https://twig.symfony.com/doc/1.x/advanced.html#automatic-escaping) [Twig 2](https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping)
> **[info] Default Value**
> 
> `array()`

#### register_globals
__Auto-reigster globals__
Register global variables, these will be available in all templates and macros.
> **[info] Default Value**
> 
> `array()`

### Asset & Metatag

#### global_meta
__Global meta array__
This lets you add global metatag's to the metatag array.
Example:
```php
array(
	array('name' => 'utf-8', 'value' => '', 'attribute' => 'charset'),
	array('name' => 'keywords', 'value' => 'twiggy, twig, template, layout, codeigniter'),
	array('name' => 'attribute', 'value' => 'width=device-width, initial-scale=1', 'attribute' => 'name'),
)
```
This will render on every page that use `{{meta}}`:
```html
<meta charset="utf-8">
<meta name="keywords" value="twiggy, twig, template, layout, codeigniter">
<meta name="viewport" content="width=device-width, initial-scale=1">
```
> **[info] Default Value**
> 
> `array()`

#### global_asset
__Global asset array__
This lets you add global asset's to the asset array.
Example:
```php
array(
	array('name' => 'attribute', 'value' => 'width=device-width, initial-scale=1', 'attribute' => 'name'),
)
```
This will render on every page that use `{{meta}}`:
```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```
> **[info] Default Value**
> 
> `array()`

#### render_all_assets
__Render Assets incl. Groups__
If you activate this any assets including group asset get rendered through {{asset}} in layout/template
> **[info] Default Value**
> 
> `FALSE`

### Folder/File Structure

#### template_file_ext
__Template File Extension__
This lets you define the extension for template files. It doesn't affect how Twiggy deals with templates but this may help you if you want to distinguish different kinds of templates. For example, for CodeIgniter you may use `*.html.twig` template files and `*.html.jst` for js templates.
> **[info] Default Value**
> 
> `'.html.twig'`

#### themes_base_dir
__Themes Base Dir__
Directory where themes are located at. This path is relative to
CodeIgniter's base directory OR module's base directory. For example:
```php
$config['twiggy']['themes_base_dir'] = 'themes/';
```
It will actually mean that themes should be placed at:
```
{APPPATH}/themes/ and {APPPATH}/modules/{some_module}/themes/.
```
NOTE: modules do not necessarily need to be in `{APPPATH}/modules/` as Twiggy will figure out the paths by itself. That way you can package your modules with themes.
Also, do not forget the trailing slash!
> **[info] Default Value**
> 
> `'views/'`

#### default_theme 
__Default theme__
> **[info] Default Value**
> 
> `'default'`

#### default_layout 
__Default layout__
> **[info] Default Value**
> 
> `'index'`

#### default_template 
__Default template__
> **[info] Default Value**
> 
> `'index'`

#### twig_cache_dir
__Default Cache Directory__
Path to the cache folder for compiled twig templates. It is relative to CodeIgniter's base directory.
> **[info] Default Value**
> 
> `APPPATH . 'cache/twig/'`

#### include_apppath
__Include APPPATH__
This lets you include the APPPATH for the themes base directory (only for the application itself, not the modules). See the example below.
Suppose you have:
```php
$config['twiggy']['themes_base_dir'] = 'views/';
$config['twiggy']['include_apppath'] = TRUE;
```
Then the path will be {APPPATH}/views/ but if you set this option to FALSE, then you will have views/.
This is useful for when you want to have the themes folder outside the application (APPPATH) folder.
> **[info] Default Value**
> 
> `TRUE`

#### delimiters
__Twig Syntax Delimiters__
If you don't like the default Twig syntax delimiters or if they collide with other languages (for example, you use handlebars.js in your templates), here you can change them.
Ruby erb style:
```php
array(
	'tag_comment' 	=> array('<%#', '#%>'),
	'tag_block'   	=> array('<%', '%>'),
	'tag_variable'	=> array('<%=', '%>')
)
```

Smarty style:
```php
array(
   'tag_comment' 	=> array('{*', '*}'),
   'tag_block'   	=> array('{', '}'),
   'tag_variable'	=> array('{$', '}'),
)
```
> **[info] Default Value**
> 
> {% raw %}
> ```php
> array(
> 'tag_comment' => array('{#', '#}'),
> 'tag_block' => array('{%', '%}'),
> 'tag_variable' => array('{{', '}}')
> )
> ```
> {% endraw %}

### Twig Environment

#### load_twig_engine
__Load Twig without Composer__
This lets you activate that Twiggy loads the Twig engine instead of composers autoload. 
Options: `TRUE`, `'old_way'`, `FALSE`
> **[info] Default Value**
> 
> `FALSE`

[More Details here](install.md#1-composer-recommend)

#### environment
__Twig Environment Options__
These are all twig-specific options that you can set. To learn more about each option, check the official documentation.
NOTE: cache option works slightly differently than in Twig. In Twig you can either set the value to FALSE to disable caching, or set the path to where the cached files should be stored (which means caching would be enabled in that case). This is not entirely convenient if you need to switch between enabled or disabled caching for debugging or other reasons.
Therefore, here the value can be either TRUE or FALSE. Cache directory can be set separately.

##### cache
__Twig Caching__
An absolute path where to store the compiled templates, or `false` to disable caching.
> **[info] Default Value**
> 
> `FALSE`

##### debug
__Twig Debugging__
When set to true, the generated templates have a `__toString()` method that you can use to display the generated nodes.
> **[info] Default Value**
> 
> `FALSE`

##### charset
__Twig Charset__
The charset used by the templates.
> **[info] Default Value**
> 
> `'utf-8'`

##### base_template_class
__Twig Base Template Class__
The base template class to use for generated templates.
> **[info] Default Value**
> 
> `'Twig_Template'`

##### auto_reload
__Twig Auto Reload__
When developing with Twig, it's useful to recompile the template whenever the source code changes. If you don't provide a value for the `auto_reload` option, it will be determined automatically based on the `debug` value.
> **[info] Default Value**
> 
> `NULL`

##### strict_variables
__Twig Strict Variables__
If set to `false`, Twig will silently ignore invalid variables (variables and or attributes/methods that do not exist) and replace them with a `null` value. When set to `true`, Twig throws an exception instead (default to `false`).
> **[info] Default Value**
> 
> `FALSE`

##### autoescape
__Twig Autoescaping__
Sets the default auto-escaping strategy (`name`, `html`, `js`, `css`, `url`, `html_attr`, or a PHP callback that takes the template "filename" and returns the escaping strategy to use -- the callback cannot be a function name to avoid collision with built-in escaping strategies); set it to `false` to disable auto-escaping. The `name` escaping strategy determines the escaping strategy to use for a template based on the template filename extension (this strategy does not incur any overhead at runtime as auto-escaping is done at compilation time.)
> **[info] Default Value**
> 
> `FALSE`

##### optimizations
__Twig Optimizations__
A flag that indicates which optimizations to apply (default to `-1` -- all optimizations are enabled; set it to `0` to disable).
> **[info] Default Value**
> 
> `-1`
>
