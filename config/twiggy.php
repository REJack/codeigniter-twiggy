<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Twiggy - Twig template engine implementation for CodeIgniter
 *
 * Twiggy is not just a simple implementation of Twig template engine
 * for CodeIgniter. It supports themes, layouts, templates for regular
 * apps and also for apps that use HMVC (module support).
 *
 * @package   			CodeIgniter
 * @subpackage			Twiggy
 * @author    			(Original Author) Edmundas Kondrašovas <as@edmundask.lt>
 * @author    			Raphael "REJack" Jackstadt <info@rejack.de>
 * @license   			http://www.opensource.org/licenses/MIT
 * @version   			0.9.8
 * @copyright 			Copyright (c) 2012-2014 Edmundas Kondrašovas <as@edmundask.lt>
 * @copyright 			Copyright (c) 2015-2017 Raphael "REJack" Jackstadt <info@rejack.de>
 */

/*
|--------------------------------------------------------------------------
| Global meta array
|--------------------------------------------------------------------------
*/

$config['twiggy']['global_meta'] = array
(

);


/*
|--------------------------------------------------------------------------
| Global asset array
|--------------------------------------------------------------------------
*/

$config['twiggy']['global_asset'] = array
(

);


/*
|--------------------------------------------------------------------------
| Title separator
|--------------------------------------------------------------------------
|
| Lets you specify the separator used in separating sections of the title
| variable.
|
*/

$config['twiggy']['title_separator'] = ' | ';


/*
|--------------------------------------------------------------------------
| Auto-reigster functions
|--------------------------------------------------------------------------
|
| Here you can list all the functions that you want Twiggy to automatically
| register them for you.
|
| NOTE: only registered functions can be used in Twig templates.
|
*/

$config['twiggy']['register_functions'] = array
(

);


/*
|--------------------------------------------------------------------------
| Auto-reigster safe functions
|--------------------------------------------------------------------------
|
| Here you can list all the functions that you want Twiggy to automatically
| register them for you and automatic escapes the output.
|
| NOTE: only registered functions can be used in Twig templates.
|
| NOTE: More details in twig documentation.
| Twig 1: https://twig.symfony.com/doc/1.x/advanced.html#automatic-escaping
| Twig 2: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
|
*/

$config['twiggy']['register_safe_functions'] = array
(

);


/*
|--------------------------------------------------------------------------
| Auto-reigster filters
|--------------------------------------------------------------------------
|
| Much like with functions, list filters that you want Twiggy to
| automatically register them for you.
|
| NOTE: only registered filters can be used in Twig templates. Also, keep
| in mind that a filter is nothing more than just a regular function that
| acceps a string (value) as a parameter and outputs a modified/new string.
|
*/

$config['twiggy']['register_filters'] = array
(

);


/*
|--------------------------------------------------------------------------
| Auto-reigster safe filters
|--------------------------------------------------------------------------
|
| Much like with functions, list filters that you want Twiggy to
| automatically register them for you and automatic escapes the output.
|
| NOTE: only registered filters can be used in Twig templates. Also, keep
| in mind that a filter is nothing more than just a regular function that
| acceps a string (value) as a parameter and outputs a modified/new string.
|
| NOTE: More details in twig documentation.
| Twig 1: https://twig.symfony.com/doc/1.x/advanced.html#automatic-escaping
| Twig 2: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
|
*/

$config['twiggy']['register_safe_filters'] = array
(

);


/*
|--------------------------------------------------------------------------
| Auto-reigster globals
|--------------------------------------------------------------------------
|
| Register global variables, these will be available in all templates and macros.
|
| Example:
| "foo" => "hello"
|
*/

$config['twiggy']['register_globals'] = array
(

);


/*
|--------------------------------------------------------------------------
| Load Twig without Composer
|--------------------------------------------------------------------------
|
| This lets you activate that Twiggy loads the Twig engine instead of
| composers autoload. 
| 1. Create a new folder 'vendors' in 'application'.
| 2. Download Twig from http://twig.sensiolabs.org/. 
| (Click on the version number after 'Stable:' like '1.24.0'.)
| 3. Extract the downloaded .tgz file.
| 4. Copy the 'Twig' folder from the 'Twig-X.XX.X/lib/' folder into the 
| 'vendors' folder.
|
| Options: TRUE, 'old_way', FALSE
| (old_way is for easy moving from edmundask/codeigniter-twiggy)
|
*/

$config['twiggy']['load_twig_engine'] = false;


/*
|--------------------------------------------------------------------------
| Syntax Delimiters
|--------------------------------------------------------------------------
|
| If you don't like the default Twig syntax delimiters or if they collide
| with other languages (for example, you use handlebars.js in your
| templates), here you can change them.
|
| Ruby erb style:
|
|	'tag_comment' 	=> array('<%#', '#%>'),
|	'tag_block'   	=> array('<%', '%>'),
|	'tag_variable'	=> array('<%=', '%>')
|
| Smarty style:
|
|    'tag_comment' 	=> array('{*', '*}'),
|    'tag_block'   	=> array('{', '}'),
|    'tag_variable'	=> array('{$', '}'),
|
*/

$config['twiggy']['delimiters'] = array
(
    'tag_comment' 	=> array('{#', '#}'),
    'tag_block'   	=> array('{%', '%}'),
    'tag_variable'	=> array('{{', '}}')
);


/*
|--------------------------------------------------------------------------
| Environment Options
|--------------------------------------------------------------------------
|
| These are all twig-specific options that you can set. To learn more about
| each option, check the official documentation.
|
| NOTE: cache option works slightly differently than in Twig. In Twig you
| can either set the value to FALSE to disable caching, or set the path
| to where the cached files should be stored (which means caching would be
| enabled in that case). This is not entirely convenient if you need to
| switch between enabled or disabled caching for debugging or other reasons.
|
| Therefore, here the value can be either TRUE or FALSE. Cache directory
| can be set separately.
|
*/

$config['twiggy']['environment']['cache']              	= FALSE;
$config['twiggy']['environment']['debug']              	= FALSE;
$config['twiggy']['environment']['charset']            	= 'utf-8';
$config['twiggy']['environment']['base_template_class']	= 'Twig_Template';
$config['twiggy']['environment']['auto_reload']        	= NULL;
$config['twiggy']['environment']['strict_variables']   	= FALSE;
$config['twiggy']['environment']['autoescape']         	= FALSE;
$config['twiggy']['environment']['optimizations']      	= -1;

/*
|--------------------------------------------------------------------------
| Using all user defined functions
|--------------------------------------------------------------------------
| Auto-register all user defined functions.
| If set to TRUE make sure you don't have camelCase functions
| example: having like  function setId(){}  will result in changing its usage inside
| twig as {{ setid() }} because PHP's get_defined_functions() function returns any function name lower-cased.
| By default it is being set to FALSE
*/
$config['twiggy']['use_user_defined_functions'] = FALSE;


/*
|--------------------------------------------------------------------------
| Template file extension
|--------------------------------------------------------------------------
|
| This lets you define the extension for template files. It doesn't affect
| how Twiggy deals with templates but this may help you if you want to
| distinguish different kinds of templates. For example, for CodeIgniter
| you may use *.html.twig template files and *.html.jst for js templates.
|
*/

$config['twiggy']['template_file_ext'] = '.html.twig';


/*
|--------------------------------------------------------------------------
| Twig Cache Dir
|--------------------------------------------------------------------------
|
| Path to the cache folder for compiled twig templates. It is relative to
| CodeIgniter's base directory.
|
*/

$config['twiggy']['twig_cache_dir'] = APPPATH . 'cache/twig/';

/*
|--------------------------------------------------------------------------
| Themes Base Dir
|--------------------------------------------------------------------------
|
| Directory where themes are located at. This path is relative to
| CodeIgniter's base directory OR module's base directory. For example:
|
| $config['themes_base_dir'] = 'themes/';
|
| It will actually mean that themes should be placed at:
|
| {APPPATH}/themes/ and {APPPATH}/modules/{some_module}/themes/.
|
| NOTE: modules do not necessarily need to be in {APPPATH}/modules/ as
| Twiggy will figure out the paths by itself. That way you can package
| your modules with themes.
|
| Also, do not forget the trailing slash!
|
*/

$config['twiggy']['themes_base_dir'] = 'views/';


/*
|--------------------------------------------------------------------------
| Include APPPATH
|--------------------------------------------------------------------------
|
| This lets you include the APPPATH for the themes base directory (only for
| the application itself, not the modules). See the example below.
|
| Suppose you have:
| $config['themes_base_dir'] = 'themes/'
| $config['include_apppath'] = TRUE
|
| Then the path will be {APPPATH}/themes/ but if you set this option to
| FALSE, then you will have themes/.
|
| This is useful for when you want to have the themes folder outside the
| application (APPPATH) folder.
|
*/

$config['twiggy']['include_apppath'] = TRUE;


/*
|--------------------------------------------------------------------------
| Default theme
|--------------------------------------------------------------------------
*/

$config['twiggy']['default_theme'] = 'default';


/*
|--------------------------------------------------------------------------
| Default layout
|--------------------------------------------------------------------------
*/

$config['twiggy']['default_layout'] = 'index';


/*
|--------------------------------------------------------------------------
| Default template
|--------------------------------------------------------------------------
*/

$config['twiggy']['default_template'] = 'index';
