# CodeIgniter-Twiggy
## Library Methods

{% PHPclassDisplayer "Twiggy" %}
    Twig template engine implementation for CodeIgniter
{% endPHPclassDisplayer %}

{% PHPmethodDisplayer "render( [$template = ''] )" %}
    Render and return compiled HTML
    {% param "$template", type="string" %}
    Name of the template file
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "display( [$template = ''] )" %}
    Display the compiled HTML content
    {% param "$template", type="string" %}
    Name of the template file
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "theme($theme)" %}
    Load theme
    {% param "$theme", type="string" %}
    Name of theme to load
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "layout($name)" %}
    Set layout
    {% param "$name", type="string" %}
    Name of the layout
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "template($name)" %}
    Set template
    {% param "$name", type="string" %}
    Name of the template file
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "register_function($name [, $safe = NULL] )" %}
    Register a function in Twig environment
    {% param "$name", type="string" %}
    Name of an existing function
    {% param "$safe", type="string" %}
    If `TRUE` output gets escaped before return.
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "register_filter($name [, $safe = NULL] )" %}
    Register a filter in Twig environment
    {% param "$name", type="string" %}
    Name of an existing function
    {% param "$safe", type="string" %}
    If `TRUE` output gets escaped before return.
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set($key [, $value = NULL, $global = FALSE] )" %}
    Set data
    {% param "$key", type="mixed" %}
    Key (variable name) or an array of variable names with value
    {% param "$value", type="mixed" %}
    Data
    {% param "$global", type="bool" %}
    Is this a global variable? 
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "unset_data($key)" %}
    Unset a particular variable
    {% param "$key", type="mixed" %}
    key (variable name)
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "title($name [, $name, $name, ...])" %}
    Add string to the title
    {% param "$name", type="string" %}
    Title to add
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "append($name [, $name, $name, ...])" %}
    Append string to the title
    {% param "$name", type="string" %}
    Title to add
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "prepend($name [, $name, $name, ...])" %}
    Append string to the title
    {% param "$name", type="string" %}
    Title to add
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_title_separator($separator = ' | ')" %}
    Set title separator
    {% param "$separator", type="string" %}
    Separator
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "meta($value [, $name = '', $attribute = NULL])" %}
    Set meta data
    {% param "$value", type="string" %}
    Name of metatag
    {% param "$name", type="string" %}
    Value of metatag
    {% param "$attribute", type="string" %}
    Attribute of metatag
    {% hint %}
    Examples for meta():

Code:
```php
$this->twiggy->meta('twiggy, twig, template, layout, codeigniter', 'keywords');
$this->twiggy->meta('width=device-width, initial-scale=1', 'attribute', 'name');
$this->twiggy->meta('', 'utf-8', 'charset');
```
Result:
```html
<meta name="keywords" value="twiggy, twig, template, layout, codeigniter">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
```
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "unset_meta( [$name, $name, $name, ...] )" %}
    Unset meta data
    {% param "$name", type="string" %}
    Name of metatag
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "asset($type, $value [, $group=NULL, $extra=array()] )" %}
    Set asset data
    {% param "$type", type="string" %}
    Type of asset
    Available options: `link` & `script`
    {% param "$value", type="string" %}
    Value of asset
    {% param "$group", type="mixen" %}
    Group of asset
    {% param "$extra", type="array" %}
    Extra Options for the Asset as associative array
    Available Options for `link`: `crossorigin`, `hreflang`, `media`, `rel`, `sizes` & `type`
    Available Options for `script`: `charset`, `async`, `defer` & `type`
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "unset_asset( [$name, $name, $name, ...] )" %}
    Unset asset data
    {% param "$name", type="string" %}
    Name of asset
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "get_theme()" %}
    Get current theme
    {% return %}
    Name of the currently loaded theme as string
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "get_layout()" %}
    Get current layout
    {% return %}
    Name of the currently loaded layout as string
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "get_template()" %}
    Get current template
    {% return %}
    Name of the currently loaded template as string
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "rendered()" %}
    Check if template is already rendered
    {% return %}
    Name of the currently loaded template as string
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "get_meta( [$name = '', $compile = NULL] )" %}
    Get metatags
    {% param "$name", type="string" %}
    Name of metatag
    {% param "$name", type="bool" %}
    Whether to compile to html
    {% return %}
    array of tag(s), string (HTML) or FALSE
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "get_asset( [$name = '', $compile = NULL] )" %}
    Get assetdata
    {% param "$name", type="string" %}
    Name of the asset item
    {% param "$name", type="bool" %}
    Whether to compile to html
    {% return %}
    array of asset(s), string (HTML) or FALSE
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "__get( [$variable == 'twig'] )" %}
    Magic method __get()
    {% param "$name", type="bool" %}
    Name of variable
    {% return %}
    Name of the currently loaded template as string
{% endPHPmethodDisplayer %}
