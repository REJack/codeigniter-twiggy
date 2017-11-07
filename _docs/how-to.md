# CodeIgniter-Twiggy
## How To Use

### 1. Load Library

#### 1. CodeIgniter's autoload
Add `twiggy` to `$autoload['libraries']`.

#### 2. Controller specific
You can load Twiggy in every Controller with 
```php
$this->load->libraries('twiggy');
```

### 2. Set up directory structure

#### 1. Create default folder structure
```
+-{APPPATH}/
| +-views/
| | +-default/
| | | +-_layouts/
```
NOTE: `{APPPATH}` is the folder where all your controllers, models and other neat stuff is placed. By default that folder is called `application`.

#### 2. Creating the default layout
Create a default layout `index.html.twig` and place it in `_layouts` folder (default: `views/default/_layout`)

Add this lines to the new file:
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <title>Default layout</title>
    </head>
    <body>

        {% block content %}

        {% endblock %}

    </body>
</html>
```

#### 3. Creating the default template
Create a default template file `index.html.twig` at the root of default `theme` folder (default: `views/default`).

Add this lines to the new file:
```html
{% extends _layout %}

{% block content %}

    Default template file.

{% endblock %}
```

#### 4. End folder structure
You should end up with a structure like this:
```
+-{APPPATH}/
| +-themes/
| | +-default/
| | | +-_layouts/
| | | | +-index.hml.twig
| | | +-index.html.twig
```


### 3. Display the template
```php
$this->twiggy->display();
```

### 4. What's next?
In the example above we only displayed the default template and layout. Obviously, you can create as many layouts and templates as you want. For example, create a new template file `welcome.html.twig` and load it before sending the output to the browser.
```php
// Whoah, methoding chaining FTW!
$this->twiggy->template('welcome')->display();
```
Notice that you only need to specify the name of the template (without the extension `*.html.twig`).