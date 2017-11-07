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
 * @version   			0.9.9
 * @copyright 			Copyright (c) 2012-2014 Edmundas Kondrašovas <as@edmundask.lt>
 * @copyright 			Copyright (c) 2015-2017 Raphael "REJack" Jackstadt <info@rejack.de>
 */

// Title
$config['twiggy']['title_separator'] = ' | ';
$config['twiggy']['title_adding_method'] = 'append';

// Functions, Filters & Globals
$config['twiggy']['register_functions'] = array();
$config['twiggy']['use_user_defined_functions'] = FALSE;
$config['twiggy']['register_safe_functions'] = array();

$config['twiggy']['register_filters'] = array();
$config['twiggy']['register_safe_filters'] = array();

$config['twiggy']['register_globals'] = array();

// Meta & Asset
$config['twiggy']['global_meta'] = array();
$config['twiggy']['global_asset'] = array();

$config['twiggy']['render_all_assets'] = false;

// Folder/File Structure
$config['twiggy']['template_file_ext'] = '.html.twig';

$config['twiggy']['themes_base_dir'] = 'views/';

$config['twiggy']['default_theme'] = 'default';
$config['twiggy']['default_layout'] = 'index';
$config['twiggy']['default_template'] = 'index';

$config['twiggy']['twig_cache_dir'] = APPPATH . 'cache/twig/';

$config['twiggy']['include_apppath'] = TRUE;

$config['twiggy']['delimiters'] = array(
    'tag_comment' 	=> array('{#', '#}'),
    'tag_block'   	=> array('{%', '%}'),
    'tag_variable'	=> array('{{', '}}')
);

// Twig Environment
$config['twiggy']['load_twig_engine'] = false;

$config['twiggy']['environment']['cache']              	= FALSE;
$config['twiggy']['environment']['debug']              	= FALSE;
$config['twiggy']['environment']['charset']            	= 'utf-8';
$config['twiggy']['environment']['base_template_class']	= 'Twig_Template';
$config['twiggy']['environment']['auto_reload']        	= NULL;
$config['twiggy']['environment']['strict_variables']   	= FALSE;
$config['twiggy']['environment']['autoescape']         	= FALSE;
$config['twiggy']['environment']['optimizations']      	= -1;
