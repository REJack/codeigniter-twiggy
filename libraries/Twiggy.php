<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Twiggy - Twig template engine implementation for CodeIgniter
 *
 * Twiggy is not just a simple implementation of Twig template engine
 * for CodeIgniter. It supports themes, layouts, templates for regular
 * apps and also for apps that use HMVC (module support).
 *
 * @package             CodeIgniter
 * @subpackage          Twiggy
 * @author              (Original Author) Edmundas Kondrašovas <as@edmundask.lt>
 * @author              Raphael "REJack" Jackstadt <info@rejack.de>
 * @license             http://www.opensource.org/licenses/MIT
 * @version             1.0.0
 * @copyright           Copyright (c) 2012-2014 Edmundas Kondrašovas <as@edmundask.lt>
 * @copyright           Copyright (c) 2015-2017 Raphael "REJack" Jackstadt <info@rejack.de>
 */

if ( ! defined('TWIGGY_ROOT'))
{
    define('TWIGGY_ROOT', dirname(dirname(__FILE__)));
}

class Twiggy {

    private $CII;
    private $_config = array();
    private $_template_locations = array();
    private $_data = array();
    private $_globals = array();
    private $_themes_base_dir;
    private $_theme;
    private $_layout;
    private $_template;
    private $_twig;
    private $_twig_loader;
    private $_module;
    private $_meta = array();
    private $_asset = array();
    private $_rendered = FALSE;
    private $system_register_globals = array(
        "SHOW_DEBUG_BACKTRACE" => SHOW_DEBUG_BACKTRACE,
        "BASEPATH" => BASEPATH
    );
    private $system_register_functions = array('get_class', 'defined', 'isset', 'realpath', 'strpos', 'debug_backtrace');
    private $system_register_safe_functions = array('render_assets');


    public function __construct()
    { 
        log_message('debug', 'Twiggy: library initialized');

        $this->CII =& get_instance();
        if (@$this->CII->load)
        {
            $this->CII->load->config('twiggy');
            $this->_config = $this->CII->config->item('twiggy');
        }
        else
        {
            $this->_config = $this->get_config(array('use_user_defined_functions' => FALSE));
        }

        if ($this->_config['load_twig_engine'] === "old_way")
        {
            require_once(TWIGGY_ROOT . '/vendor/Twig/lib/Twig/Autoloader.php');
        } 
        else if ($this->_config['load_twig_engine'] === TRUE)
        {
            require_once(TWIGGY_ROOT . '/vendor/Twig/Autoloader.php');
        } 

        if ($this->_config['load_twig_engine'] !== FALSE)
        {
            Twig_Autoloader::register();
        }

        $this->_themes_base_dir = ($this->_config['include_apppath']) ? APPPATH . $this->_config['themes_base_dir'] : $this->_config['themes_base_dir'];
        $this->_set_template_locations($this->_config['default_theme']);

        try
        {
            $this->_twig_loader = new Twig_Loader_Filesystem($this->_template_locations);
        }
        catch(Twig_Error_Loader $e)
        {
            log_message('error', 'Twiggy: failed to load the default theme');
            show_error($e->getRawMessage());
        }

        $this->_config['environment']['cache'] = ($this->_config['environment']['cache']) ? $this->_config['twig_cache_dir'] : FALSE;
        $this->_twig = new Twig_Environment($this->_twig_loader, $this->_config['environment']);

        $this->theme($this->_config['default_theme'])
             ->layout($this->_config['default_layout'])
             ->template($this->_config['default_template']);

        if ($this->_config['use_user_defined_functions'] == TRUE)
        {
            $this->CII->load->helper('array');
            foreach (element('user', get_defined_functions()) as $func_name)
            {
                if (strpos($func_name, 'twig') === FALSE)
                {
                    $this->register_function($func_name);
                }
            }
        }

        if (count($this->_config['register_functions']) > 0)
        {
            foreach ($this->_config['register_functions'] as $function)
            {
                $this->register_function($function);
            }
        }
        
        if (count($this->_config['register_safe_functions']) > 0)
        {
            foreach ($this->_config['register_safe_functions'] as $function)
            {
                $this->register_function($function, TRUE);
            }
        }
        
        if (count($this->_config['register_filters']) > 0)
        {
            foreach ($this->_config['register_filters'] as $filter)
            {
                $this->register_filter($filter);
            }
        }
        
        if (count($this->_config['register_safe_filters']) > 0)
        {
            foreach ($this->_config['register_safe_filters'] as $filter)
            {
                $this->register_filter($filter, TRUE);
            }
        }
        
        if (count($this->_config['register_globals']) > 0){
            foreach ($this->_config['register_globals'] as $k => $v) 
            {
                $this->set($k, $v, TRUE);
            }
        }

        foreach ($this->system_register_globals as $k => $v) 
        {
            $this->set($k, $v, TRUE);
        }
        
        foreach ($this->system_register_functions as $function)
        {
            $this->register_function($function);
        }
        
        foreach ($this->system_register_safe_functions as $function)
        {
            $this->register_function($function, TRUE);
        }

        $this->_globals['title'] = NULL;
        $this->_globals['meta'] = NULL;
        $this->_globals['asset'] = NULL;
        $this->_meta = $this->_config['global_meta'];
        $this->_asset = $this->_config['global_asset'];
    }

    public function render($template = '')
    {
        if ( ! empty($template)) $this->template($template);

        try
        {
            return $this->_load()->render($this->_data);
        }
        catch(Twig_Error_Loader $e)
        {
            show_error($e->getRawMessage());
        }
    }

    public function display($template = '')
    {
        if ( ! empty($template)) 
        {
            $this->template($template);
        }

        try
        {
            $this->_load()->display($this->_data);
        }
        catch(Twig_Error_Loader $e)
        {
            show_error($e->getRawMessage());
        }
    }

    public function theme($theme)
    {
        if ( ! is_dir(realpath($this->_themes_base_dir. $theme)))
        {
            log_message('error', 'Twiggy: requested theme '. $theme .' has not been loaded because it does not exist.');
            show_error("Theme does not exist in {$this->_themes_base_dir}{$theme}.");
        }

        $this->_theme = $theme;
        $this->_set_template_locations($theme);
        return $this;
    }

    public function layout($name)
    {
        $this->_layout = $name;
        $this->_twig->addGlobal('_layout', '_layouts/'. $this->_layout . $this->_config['template_file_ext']);
        return $this;
    }

    public function template($name)
    {
        $this->_template = $name;
        return $this;
    }

    public function register_function($name, $safe = NULL)
    {
        if ($safe)
        {
            if (substr(Twig_Environment::VERSION, 0, 1) == '2')
            {
                $function = new Twig_Function($name, $name, array('is_safe' => array('html')));
            }
            else
            {
                $function = new Twig_SimpleFunction($name, $name, array('is_safe' => array('html')));
            }

            $this->_twig->addFunction($function);
        }
        else 
        {
            if (substr(Twig_Environment::VERSION, 0, 1) == '2')
            {
                $this->_twig->addFunction(new \Twig_Function($name, $name));
            }
            else
            {
                $this->_twig->addFunction($name, new Twig_Function_Function($name));
            }
        }
        return $this;
    }

    public function register_filter($name, $safe = NULL)
    {
        if ($safe)
        {
            if (substr(Twig_Environment::VERSION, 0, 1) == '2')
                $filter = new Twig_Filter($name, $name, array('is_safe' => array('html')));
            else
                $filter = new Twig_SimpleFilter($name, $name, array('is_safe' => array('html')));

            $this->_twig->addFilter($filter);
        }
        else 
        {
            if (substr(Twig_Environment::VERSION, 0, 1) == '2')
                $this->_twig->addFilter(new \Twig_Filter($name, $name));
            else
                $this->_twig->addFilter($name, new Twig_Filter_Function($name)); 
        }
        
        return $this;
    }

    public function set($key, $value = NULL, $global = FALSE)
    {   
        if (is_array($key))
        {
            foreach($key as $k => $v) $this->set($k, $v, $global);
        }
        else
        {
            if ($global)
            {
                $this->_twig->addGlobal($key, $value);
                $this->_globals[$key] = $value;
            }
            else
            {
                 $this->_data[$key] = $value;
            }
        }

        return $this;
    }

    public function unset_data($key)
    {  
        if (array_key_exists($key, $this->_data))
        {
            unset($this->_data[$key]);
        }

        return $this;
    }

    public function title()
    {  
        if (func_num_args() > 0)
        {
            $args = func_get_args();
            call_user_func_array(array($this, $this->_config['title_adding_method']), $args);
        }

        return $this;
    }

    public function append()
    {  
        $args = func_get_args();
        $title = implode($this->_config['title_separator'], $args);

        if (empty($this->_globals['title']))
        {
            $this->set('title', $title, TRUE);
        }
        else
        {
            $this->set('title', $this->_globals['title'] . $this->_config['title_separator'] . $title, TRUE);
        }

        return $this;
    }

    public function prepend()
    {  
        $args = func_get_args();
        $title = implode($this->_config['title_separator'], $args);

        if (empty($this->_globals['title']))
        {
            $this->set('title', $title, TRUE);
        }
        else
        {
            $this->set('title', $title . $this->_config['title_separator'] . $this->_globals['title'], TRUE);
        }

        return $this;
    }

    public function set_title_separator($separator = ' | ')
    {
        $this->_config['title_separator'] = $separator;
        return $this;
    }

    public function meta($value, $name = '', $attribute = NULL)
    {
        $this->_meta[$name] = array('name' => $name, 'value' => $value, 'attribute' => $attribute);
        return $this;
    }

    public function unset_meta()
    {   
        if (func_num_args() > 0)
        {
            $args = func_get_args();

            foreach ($args as $arg)
            {
                if (array_key_exists($arg, $this->_meta)) unset($this->_meta[$arg]);
            }
        }
        else
        {
            $this->_meta = array();
        }

        return $this;
    }

    public function asset($type, $value, $group=NULL, $extra=array())
    {   
        if ($group)
        {
            $this->_asset[$group][] = array('type' => $type, 'value' => $value, 'extra' => $extra);
        }
        else
        {
            $this->_asset[] = array('type' => $type, 'value' => $value, 'extra' => $extra);
        }

        return $this;
    }

    public function unset_asset()
    {
        if (func_num_args() > 0)
        {
            $args = func_get_args();

            foreach ($args as $arg)
            {
                if (array_key_exists($arg, $this->_asset)) unset($this->_asset[$arg]);
            }
        }
        else
        {
            $this->_asset = array();
        }

        return $this;
    }

    public function get_theme()
    {
        return $this->_theme;
    }

    public function get_layout()
    {
        return $this->_layout;
    }

    public function get_template()
    {
        return $this->_template;
    }

    public function rendered()
    {
        return $this->_rendered;
    }

    public function get_meta($name = '', $compile = NULL)
    {
        if (empty($name))
        {
            return ($compile) ? $this->_compile_metadata() : $this->_meta;
        }
        else
        {
            if (array_key_exists($name, $this->_meta))
            {
                return ($compile) ? $this->_meta_to_html($this->_meta[$name]) : $this->_meta[$name];
            }

            return FALSE;
        }
    }

    public function get_asset($name = '', $compile = FALSE)
    {
        if (empty($name))
        {
            return ($compile) ? $this->_compile_assetdata() : $this->_asset;
        }
        else
        {
            if (array_key_exists($name, $this->_asset))
            {
                return ($compile) ? $this->_asset_to_html($this->_asset[$name]) : $this->_asset[$name];
            }

            return FALSE;
        }
    }

    public function __get($variable = 'twig')
    {
        if ($variable == 'twig') 
        {
            return $this->_twig;
        }

        if (array_key_exists($variable, $this->_globals))
        {
            return $this->_globals[$variable];
        }
        elseif (array_key_exists($variable, $this->_data))
        {
            return $this->_data[$variable];
        }

        return FALSE;
    }

    private function _load()
    {
        $this->set('meta', $this->_compile_metadata(), TRUE);
        $this->set('asset', $this->_compile_assetdata(), TRUE);
        $this->_rendered = TRUE;
        $this->_twig->setLexer(new Twig_Lexer($this->_twig, $this->_config['delimiters']));

        if (substr(Twig_Environment::VERSION, 0, 1) == '2')
        {
            return $this->_twig->loadTemplate($this->_template . $this->_config['template_file_ext']);
        }
        else
        {
            return $this->_twig->load($this->_template . $this->_config['template_file_ext']);
        }
    }

    private function _set_template_locations($theme)
    {
        if (method_exists($this->CII, 'router') && method_exists($this->CII->router, 'fetch_module'))
        {
            $this->_module = $this->CII->router->fetch_module();

            if ( ! empty($this->_module))
            {
                if (!class_exists('Modules'))
                {
                    $module_locations = $this->CII->config->item('modules_locations');
                }
                else
                {
                    $module_locations = Modules::$locations;
                }
                foreach ($module_locations as $loc => $offset)
                {
                    if ( ! class_exists('Modules')) 
                    {
                        if (is_dir($offset.$this->_module.'/'.$this->_config['themes_base_dir'].$theme))
                        {
                            $this->_template_locations[] = $offset.$this->_module.'/'.$this->_config['themes_base_dir'].$theme;
                        }
                    }
                    else
                    {
                        if (is_dir($loc.$this->_module.'/'.$this->_config['themes_base_dir'].$theme))
                        {
                            $this->_template_locations[] = $loc.$this->_module.'/'.$this->_config['themes_base_dir'].$theme;
                        }
                    }
                }
            }
        }

        $this->_template_locations[] =  $this->_themes_base_dir.$theme;

        if (is_object($this->_twig_loader))
        {
            $this->_template_locations = array_reverse($this->_template_locations);
            $this->_twig_loader->setPaths($this->_template_locations);
        }
    }

    private function _compile_metadata()
    {
        $html = '';

        foreach ($this->_meta as $meta)
        {
            $html .= $this->_meta_to_html($meta);
        }
        
        return $html;
    }

    private function _meta_to_html($meta)
    {
        if (empty($meta['attribute'])){
           return "<meta name=\"".$meta['name']."\ value=\"".$meta['value']."\">\n";
        }
        else
        {
            if ($meta['value'] !== '')
            {
                return '<meta '.$meta['attribute'].'="'.$meta['name'].'" content="'.$meta['value'].'">\n';
            }
            else
            {
                return '<meta '.$meta['attribute'].'="'.$meta['name'].'">\n';
            }
        }
    }

    private function _compile_assetdata()
    {
        $html = '';

        foreach ($this->_asset as $asset)
        {
            $html .= $this->_asset_to_html($asset);
        }

        return $html;
    }

    private function _compile_group_assetdata($group)
    {
        if ( ! isset($this->_asset[$group]))
        {
            return;
        }

        $html = '';
        
        foreach ($this->_asset[$group] as $asset)
        {
            $html .= $this->_asset_to_html($asset);
        }

        return $html;
    }

    private function _asset_to_html($asset)
    {
        if ( ! isset($asset['type']) && isset($asset[0]))
        {
            if ($this->_config['render_all_assets'])
            {
                $asset = $asset[0];
            }
            else
            {
                return;
            } 
        }

        if ($asset['type'] == 'script')
        {
            $extra = '';

            if (isset($asset['extra']))
            {
                if (isset($asset['extra']['charset']))
                {
                    $extra .= ' charset="'.$asset['extra']['charset'].'"';
                }

                if (isset($asset['extra']['async']))
                {
                    $extra .= ' async';
                }

                if (isset($asset['extra']['defer']))
                {
                    $extra .= ' defer="'.$asset['extra']['defer'].'"';
                }

                if (isset($asset['extra']['type']))
                {
                    $extra .= ' type="'.$asset['extra']['type'].'"';
                }
                else
                {
                    $extra .= ' type="text/javascript"';
                }
            }

            return '<script src="'.$asset['value'].'"'.$extra.'></script>'."\n";        
        }
        else if ($asset['type'] == 'link')
        {
            $extra = '';

            if (isset($asset['extra']))
            {
                if (isset($asset['extra']['crossorigin']))
                {
                    $extra .= ' crossorigin="'.$asset['extra']['crossorigin'].'"';
                }
                if (isset($asset['extra']['hreflang']))
                {
                    $extra .= ' hreflang="'.$asset['extra']['hreflang'].'"';
                }

                if (isset($asset['extra']['media']))
                {
                    $extra .= ' media="'.$asset['extra']['media'].'"';
                }

                if (isset($asset['extra']['rel']))
                {
                    $extra .= ' rel="'.$asset['extra']['tyrelpe'].'"';
                }
                else
                {
                    $extra .= ' rel="stylesheet"';
                }

                if (isset($asset['extra']['sizes']))
                {
                    $extra .= ' sizes="'.$asset['extra']['sizes'].'"';
                }

                if (isset($asset['extra']['type']))
                {
                    $extra .= ' type="'.$asset['extra']['type'].'"';
                }
                else
                {
                    $extra .= ' type="text/css"';
                }
            }

            return '<link href="'.$asset['value'].'"'.$extra.'>'."\n";        
        }

        return;
    }

    protected function get_config($replace = array())
    {
        static $config;

        if (empty($config))
        {
            $file_path = APPPATH.'config/twiggy.php';
            $found = FALSE;

            if (file_exists($file_path))
            {
                $found = TRUE;
                require($file_path);
            }

            if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/twiggy.php'))
            {
                require($file_path);
            }
            elseif ( ! $found)
            {
                set_status_header(503);
                echo 'The configuration file does not exist.';
                exit(3);
            }


            if ( ! isset($config) OR ! is_array($config))
            {
                set_status_header(503);
                echo 'Your config file does not appear to be formatted correctly.';
                exit(3);
            }
        }

        foreach ($replace as $key => $val)
        {
            $config['twiggy'][$key] = $val;
        }

        return $config['twiggy'];
    }
}
