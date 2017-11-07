# CodeIgniter-Twiggy
## Helper Methods

{% PHPmethodDisplayer "get_twiggy_instance()" %}
	Returns the Twiggy instance.
    {% return %}
    Instance of this class as object.
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "twig($name [, $data = NULL, $render = NULL] )" %}
	Displays a twig template - alias for the Twiggy render/display method
    {% param "$name", type="string" %}
	Name of template
    {% param "$data", type="array" %}
    Data for template
    {% param "$render", type="bool" %}
    Changes return to render() instead of display() 
    {% return %}
    If render isn't set, it echo's directly the template, else it return's the template
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_theme($name)" %}
	Set the theme for twiggy
    {% param "$name", type="string" %}
	Name of theme
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_layout($name)" %}
	Set the layout for twiggy
    {% param "$name", type="string" %}
	Name of layout
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_page_title($name)" %}
	Set the page title for twiggy
    {% param "$name", type="string" %}
	Name of page title
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "append_page_title($name)" %}
	Add a title after an already set title
    {% param "$name", type="string" %}
	Name of page title
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "prepend_page_title($name)" %}
	Add a title before an already set title
    {% param "$name", type="string" %}
	Name of page title
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_metatag($name, $value [, $attribute = 'name'])" %}
	Add a metatag to the meta array
    {% param "$name", type="string" %}
	Name of metatag
    {% param "$value", type="string" %}
	Value of metatag
    {% param "$attribute", type="string" %}
	Attribute of metatag
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "set_asset($name, $value [, $group = NULL, $extra = array()])" %}
	Add a asset to the asset array
    {% param "$name", type="string" %}
	Name of asset
    {% param "$value", type="string" %}
	Value of asset
    {% param "$group", type="string" %}
    Group of asset
    {% param "$extra", type="string" %}
    Extra options of asset
    {% return %}
    void
{% endPHPmethodDisplayer %}

{% PHPmethodDisplayer "assets( [$group = NULL] )" %}
    Display all assets or only group assets
    {% param "$group", type="string" %}
    Name of asset group
    {% return %}
    Rendered assets as link or script tag
{% endPHPmethodDisplayer %}