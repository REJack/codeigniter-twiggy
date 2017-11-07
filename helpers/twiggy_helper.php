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

if ( ! function_exists('get_twiggy_instance'))
{
    function get_twiggy_instance()
    {
        $CII =& get_instance();          
        $CII->load->library('twiggy');
        return $CII->twiggy;
    }
}

if ( ! function_exists('assets'))
{
	function assets($group = NULL)
	{
		$twiggy = get_twiggy_instance();
		return $twiggy->_compile_group_assetdata($group);
	}
}


if ( ! function_exists('twig')) {
    /**
     * Displays a twig template - alias for the Twiggy render/display method
     * @param  string $name
     * @param  array $data
     * @return void
     */
    function twig($name, $render = NULL, $data = NULL)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->template($name);
        $twiggy->set($data);

        if ( ! $render)
        {
        	return $twiggy->render();
       	}
       	else
       	{
    		return $twiggy->display();
       	}

    }
}

if ( ! function_exists('set_theme'))
{
    /**
     * Sets the theme for twiggy
     * @param  string $name
     * @return void
     */
    function set_theme($name)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->theme($name);
    }
}

if ( ! function_exists('set_layout'))
{
    /**
     * Sets the layout for twiggy
     * @param  string $name
     * @return void
     */
    function set_layout($name)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->layout($name);
    }
}

if ( ! function_exists('set_page_title'))
{
    /**
     * set the title of the page
     * @param string  $title
     * @param string  $append  <optional>
     * @return void
     */
    function set_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->title($title);
    }
}

if ( ! function_exists('append_page_title'))
{
    /**
     * Add a title after an already set title
     * @param  string $title
     * @return void
     */
    function append_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->append($title);
    }
}

if ( ! function_exists('prepend_page_title'))
{
    /**
     * Add a title before an already set title
     * @param  string $title
     * @return void
     */
    function prepend_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->prepend($title);
    }
}

if ( ! function_exists('set_metatag'))
{
    /**
     * Set a meta tag
     * @param string $name      name of the meta tag
     * @param string $value     content of the meta tag
     * @param string $attribute attribute of the meta tag
     * @return void
     */
    function set_metatag($name, $value, $attribute = 'name')
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->meta($name, $value, $attribute);
    }
}

if ( ! function_exists('set_asset'))
{
    /**
     * Set an asset
     * @param string $name      name of the meta tag
     * @param string $value     content of the meta tag
     * @param string $attribute attribute of the meta tag
     * @return void
     */
    function set_asset($type, $value, $name, $extra=array() )
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->asset($type, $value, $name, $extra);
     }
}
