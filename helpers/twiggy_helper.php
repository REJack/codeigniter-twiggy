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
 * @version   			1.0.0
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

if ( ! function_exists('twig'))
{
    function twig($name, $data = NULL, $render = NULL)
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
    function set_theme($name)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->theme($name);
        return;
    }
}

if ( ! function_exists('set_layout'))
{
    function set_layout($name)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->layout($name);
        return;
    }
}

if ( ! function_exists('set_page_title'))
{
    function set_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->title($title);
        return;
    }
}

if ( ! function_exists('append_page_title'))
{
    function append_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->append($title);
        return;
    }
}

if ( ! function_exists('prepend_page_title'))
{
    function prepend_page_title($title)
    {
        $twiggy = get_twiggy_instance();
        $twiggy->prepend($title);
        return;
    }
}

if ( ! function_exists('set_metatag'))
{
    function set_metatag($name, $value, $attribute = 'name')
    {
        $twiggy = get_twiggy_instance();
        $twiggy->meta($name, $value, $attribute);
        return;
    }
}

if ( ! function_exists('set_asset'))
{
    function set_asset($type, $value, $group = NULL, $extra=array())
    {
        $twiggy = get_twiggy_instance();
        $twiggy->asset($type, $value, $group, $extra);
        return;
     }
}

if ( ! function_exists('render_assets'))
{
    function render_assets($group = NULL)
    {
        $twiggy = get_twiggy_instance();
        return $twiggy->_compile_group_assetdata($group);
    }
}

