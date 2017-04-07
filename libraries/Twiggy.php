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
 * @version             0.9.6
 * @copyright           Copyright (c) 2012-2014 Edmundas Kondrašovas <as@edmundask.lt>
 * @copyright           Copyright (c) 2015-2017 Raphael "REJack" Jackstadt <info@rejack.de>
 */

if(!defined('TWIGGY_ROOT')) define('TWIGGY_ROOT', dirname(dirname(__FILE__)));

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


    public function __construct()
    {
        log_message('debug', 'Twiggy: library initialized');

        $this->CII =& get_instance();
        if (method_exists($this->CII, 'load'))
        {
            $this->CII->load->config('twiggy');
            $this->_config = $this->CII->config->item('twiggy');
        }
        else
        {
            $this->_config = $this->get_config();
        }
        if ($this->_config['load_twig_engine'] === "old_way")
        {
            require_once(TWIGGY_ROOT . '/vendor/Twig/lib/Twig/Autoloader.php');
        } 
        else if ($this->_config['load_twig_engine'] === TRUE)
        {
            require_once(TWIGGY_ROOT . '/vendor/Twig/Autoloader.php');
        } 

        Twig_Autoloader::register();

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

        if(count($this->_config['register_functions']) > 0)
        {
            foreach($this->_config['register_functions'] as $function) $this->register_function($function);
        }
        if(count($this->_config['register_filters']) > 0)
        {
            foreach($this->_config['register_filters'] as $filter) $this->register_filter($filter);
        }
        if(count($this->_config['register_globals']) > 0){
            foreach($this->_config['register_globals'] as $k => $v) $this->set($k, $v, TRUE);
        }

        foreach($this->system_register_globals as $k => $v) $this->set($k, $v, TRUE);
        foreach($this->system_register_functions as $function) $this->register_function($function);

        $this->_twig->setLexer(new Twig_Lexer($this->_twig, $this->_config['delimiters']));
        $this->_globals['title'] = NULL;
        $this->_globals['meta'] = NULL;
        $this->_globals['asset'] = NULL;
        $this->_meta = $this->_config['global_meta'];
        $this->_asset = $this->_config['global_asset'];
    }

    /**
     * Set data
     *
     * @access	public
     * @param 	mixed  	key (variable name) or an array of variable names with values
     * @param 	mixed  	data
     * @param 	boolean	(optional) is this a global variable?
     * @return	object 	instance of this class
     */

    public function set($key, $value = NULL, $global = FALSE)
    {
        if(is_array($key))
        {
            foreach($key as $k => $v) $this->set($k, $v, $global);
        }
        else
        {
            if($global)
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

    /**
     * Unset a particular variable
     *
     * @access	public
     * @param 	mixed  	key (variable name)
     * @return	object 	instance of this class
     */

    public function unset_data($key)
    {
        if(array_key_exists($key, $this->_data)) unset($this->_data[$key]);
        return $this;
    }

    /**
     * Set title
     *
     * @access	public
     * @param 	string
     * @return	object 	instance of this class
     */

    public function title()
    {
        if(func_num_args() > 0)
        {
            $args = func_get_args();
            call_user_func_array(array($this, 'append'), $args);
        }

        return $this;
    }

    /**
     * Append string to the title
     *
     * @access	public
     * @param 	string
     * @return	object 	instance of this class
     */

    public function append()
    {
        $args = func_get_args();
        $title = implode($this->_config['title_separator'], $args);

        if(empty($this->_globals['title']))
        {
            $this->set('title', $title, TRUE);
        }
        else
        {
            $this->set('title', $this->_globals['title'] . $this->_config['title_separator'] . $title, TRUE);
        }

        return $this;
    }

    /**
     * Prepend string to the title
     *
     * @access	public
     * @param 	string
     * @return	object 	instance of this class
     */

    public function prepend()
    {
        $args = func_get_args();
        $title = implode($this->_config['title_separator'], $args);

        if(empty($this->_globals['title']))
        {
            $this->set('title', $title, TRUE);
        }
        else
        {
            $this->set('title', $title . $this->_config['title_separator'] . $this->_globals['title'], TRUE);
        }

        return $this;
    }

    /**
     * Set title separator
     *
     * @access	public
     * @param 	string	separator
     * @return	object 	instance of this class
     */

    public function set_title_separator($separator = ' | ')
    {
        $this->_config['title_separator'] = $separator;
        return $this;
    }

    /**
     * Set meta data
     *
     * @access	public
     * @param 	string	name
     * @param	string	value
     * @param	string	(optional) name of the meta tag attribute
     * @return	object 	instance of this class
     */

    public function meta($value, $name = '', $attribute = 'name')
    {
        $this->_meta[$name] = array('name' => $name, 'value' => $value, 'attribute' => $attribute);
        return $this;
    }

    /**
     * Unset meta data
     *
     * @access	public
     * @param 	string	(optional) name of the meta tag
     * @return	object	instance of this class
     */

    public function unset_meta()
    {
        if(func_num_args() > 0)
        {
            $args = func_get_args();

            foreach($args as $arg)
            {
                if(array_key_exists($arg, $this->_meta)) unset($this->_meta[$arg]);
            }
        }
        else
        {
            $this->_meta = array();
        }

        return $this;
    }


    /**
     * Set asset data
     *
     * @access  public
     * @param   string  type
     * @param   string  name
     * @param   string  value
     * @param   string  (optional) extra array
     * @return  object  instance of this class
     */

    public function asset($type, $name, $value, $extra=array())
    {
        $this->_asset[$name] = array('type' => $type, 'value' => $value, 'extra' => $extra);
        return $this;
    }

    /**
     * Unset asset data
     *
     * @access  public
     * @param   string  (optional) name of the asset tag
     * @return  object  instance of this class
     */

    public function unset_asset()
    {
        if(func_num_args() > 0)
        {
            $args = func_get_args();

            foreach($args as $arg)
            {
                if(array_key_exists($arg, $this->_asset)) unset($this->_asset[$arg]);
            }
        }
        else
        {
            $this->_asset = array();
        }

        return $this;
    }

    /**
     * Register a function in Twig environment
     *
     * @access	public
     * @param 	string	the name of an existing function
     * @return	object	instance of this class
     */

    public function register_function($name)
    {
        $this->_twig->addFunction($name, new Twig_Function_Function($name));
        return $this;
    }

    /**
     * Register a filter in Twig environment
     *
     * @access	public
     * @param 	string	the name of an existing function
     * @return	object	instance of this class
     */

    public function register_filter($name)
    {
        $this->_twig->addFilter($name, new Twig_Filter_Function($name));
        return $this;
    }

    /**
    * Load theme
    *
    * @access	public
    * @param 	string	name of theme to load
    * @return	object	instance of this class
    */

    public function theme($theme)
    {
        if( ! is_dir(realpath($this->_themes_base_dir. $theme)))
        {
            log_message('error', 'Twiggy: requested theme '. $theme .' has not been loaded because it does not exist.');
            show_error("Theme does not exist in {$this->_themes_base_dir}{$theme}.");
        }

        $this->_theme = $theme;
        $this->_set_template_locations($theme);
        return $this;
    }

    /**
     * Set layout
     *
     * @access	public
     * @param 	string	name of the layout
     * @return	object	instance of this class
     */

    public function layout($name)
    {
        $this->_layout = $name;
        $this->_twig->addGlobal('_layout', '_layouts/'. $this->_layout . $this->_config['template_file_ext']);
        return $this;
    }

    /**
     * Set template
     *
     * @access	public
     * @param 	string	name of the template file
     * @return	object	instance of this class
     */

    public function template($name)
    {
        $this->_template = $name;
        return $this;
    }

    /**
     * Compile meta data into pure HTML
     *
     * @access	private
     * @return	string	HTML
     */

    private function _compile_metadata()
    {
        $html = '';
        foreach ($this->_meta as $meta) $html .= $this->_meta_to_html($meta);
        return $html;
    }

    /**
     * Compile asset data into pure HTML
     *
     * @access  private
     * @return  string  HTML
     */

    private function _compile_assetdata()
    {
        $html = '';
        foreach ($this->_asset as $asset) $html .= $this->_asset_to_html($asset);
        return $html;
    }

    /**
     * Convert meta tag array to HTML code
     *
     * @access	private
     * @param 	array 	meta tag
     * @return	string	HTML code
     */

    private function _meta_to_html($meta)
    {
        if (empty($meta['name'])){
           return "<meta content=\"".$meta['value']."\">\n";
        }
        else
        {
           return "<meta ".$meta['attribute']."=\"".$meta['name']."\" content=\"".$meta['value']."\">\n";
        }
    }

    /**
     * Convert asset item array to HTML code
     *
     * @access  private
     * @param   array   asset item
     * @return  string  HTML code
     */

    private function _asset_to_html($asset)
    {
        if($asset['type'] == 'script'){
            $extra = '';
            if(isset($asset['extra'])){
                if(isset($asset['extra']['charset'])){
                    $extra .= ' charset="'.$asset['extra']['charset'].'"';
                }
                if(isset($asset['extra']['async'])){
                    $extra .= ' async';
                }
                if(isset($asset['extra']['defer'])){
                    $extra .= ' defer="'.$asset['extra']['defer'].'"';
                }
                if(isset($asset['extra']['type'])){
                    $extra .= ' type="'.$asset['extra']['type'].'"';
                } else {
                    $extra .= ' type="text/javascript"';
                }
            }

            return '<script src="'.$asset['value'].'"'.$extra.'></script>'."\n";        
        } else if ($asset['type'] == 'link'){
            $extra = '';
            if(isset($asset['extra'])){
                if(isset($asset['extra']['crossorigin'])){
                    $extra .= ' crossorigin="'.$asset['extra']['crossorigin'].'"';
                }
                if(isset($asset['extra']['hreflang'])){
                    $extra .= ' hreflang="'.$asset['extra']['hreflang'].'"';
                }
                if(isset($asset['extra']['media'])){
                    $extra .= ' media="'.$asset['extra']['media'].'"';
                }
                if(isset($asset['extra']['rel'])){
                    $extra .= ' rel="'.$asset['extra']['tyrelpe'].'"';
                } else {
                    $extra .= ' rel="stylesheet"';
                }
                if(isset($asset['extra']['sizes'])){
                    $extra .= ' sizes="'.$asset['extra']['sizes'].'"';
                }
                if(isset($asset['extra']['type'])){
                    $extra .= ' type="'.$asset['extra']['type'].'"';
                } else {
                    $extra .= ' type="text/css"';
                }
            }

            return '<link href="'.$asset['value'].'"'.$extra.'>'."\n";        
        }

        return;
    }

    /**
     * Load template and return output object
     *
     * @access	private
     * @return	object	output
     */

    private function _load()
    {
        $this->set('meta', $this->_compile_metadata(), TRUE);
        $this->set('asset', $this->_compile_assetdata(), TRUE);
        $this->_rendered = TRUE;
        return $this->_twig->loadTemplate($this->_template . $this->_config['template_file_ext']);
    }

    /**
     * Render and return compiled HTML
     *
     * @access	public
     * @param 	string	(optional) template file
     * @return	string	compiled HTML
     */

    public function render($template = '')
    {
        if( ! empty($template)) $this->template($template);

        try
        {
            return $this->_load()->render($this->_data);
        }
        catch(Twig_Error_Loader $e)
        {
            show_error($e->getRawMessage());
        }
    }

    /**
     * Display the compiled HTML content
     *
     * @access	public
     * @param 	string	(optional) template file
     * @return	void
     */

    public function display($template = '')
    {
        if( ! empty($template)) $this->template($template);

        try
        {
            $this->_load()->display($this->_data);
        }
        catch(Twig_Error_Loader $e)
        {
            show_error($e->getRawMessage());
        }
    }

    /**
    * Set template locations
    *
    * @access	private
    * @param 	string	name of theme to load
    * @return	void
    */

    private function _set_template_locations($theme)
    {
        if(method_exists($this->CII, 'router') && method_exists($this->CII->router, 'fetch_module'))
        {
            $this->_module = $this->CII->router->fetch_module();

            if( ! empty($this->_module))
            {
                if (!class_exists('Modules')) {
                    $module_locations = $this->CII->config->item('modules_locations');
                }else{
                    $module_locations = Modules::$locations;
                }
                foreach($module_locations as $loc => $offset)
                {
                    if ( ! class_exists('Modules')) 
                    {
                        if (is_dir($offset.$this->_module.'/'.$this->_config['themes_base_dir'].$theme))
                            $this->_template_locations[] = $offset.$this->_module.'/'.$this->_config['themes_base_dir'].$theme;
                    }
                    else
                    {
                        if (is_dir($loc.$this->_module.'/'.$this->_config['themes_base_dir'].$theme))
                            $this->_template_locations[] = $loc.$this->_module.'/'.$this->_config['themes_base_dir'].$theme;
                    }
                }
            }
        }

        $this->_template_locations[] =  $this->_themes_base_dir.$theme;

        if(is_object($this->_twig_loader))
        {
            $this->_template_locations = array_reverse($this->_template_locations);
            $this->_twig_loader->setPaths($this->_template_locations);
        }
    }

    /**
    * Get current theme
    *
    * @access	public
    * @return	string	name of the currently loaded theme
    */

    public function get_theme()
    {
        return $this->_theme;
    }

    /**
    * Get current layout
    *
    * @access	public
    * @return	string	name of the currently used layout
    */

    public function get_layout()
    {
        return $this->_layout;
    }

    /**
    * Get template
    *
    * @access	public
    * @return	string	name of the loaded template file (without the extension)
    */

    public function get_template()
    {
        return $this->_template;
    }

    /**
    * Get metadata
    *
    * @access	public
    * @param 	string 	(optional) name of the meta tag
    * @param 	boolean	whether to compile to html
    * @return	mixed  	array of tag(s), string (HTML) or FALSE
    */

    public function get_meta($name = '', $compile = FALSE)
    {
        if(empty($name))
        {
            return ($compile) ? $this->_compile_metadata() : $this->_meta;
        }
        else
        {
            if(array_key_exists($name, $this->_meta))
            {
                return ($compile) ? $this->_meta_to_html($this->_meta[$name]) : $this->_meta[$name];
            }

            return FALSE;
        }
    }

    /**
    * Get assetdata
    *
    * @access   public
    * @param    string  (optional) name of the asset item
    * @param    boolean whether to compile to html
    * @return   mixed   array of tag(s), string (HTML) or FALSE
    */

    public function get_asset($name = '', $compile = FALSE)
    {
        if(empty($name))
        {
            return ($compile) ? $this->_compile_assetdata() : $this->_asset;
        }
        else
        {
            if(array_key_exists($name, $this->_asset))
            {
                return ($compile) ? $this->_asset_to_html($this->_asset[$name]) : $this->_asset[$name];
            }

            return FALSE;
        }
    }

    /**
    * Check if template is already rendered
    *
    * @access	public
    * @return	boolean
    */

    public function rendered()
    {
        return $this->_rendered;
    }

    /**
    * Magic method __get()
    */

    public function __get($variable)
    {
        if($variable == 'twig') return $this->_twig;
        if(array_key_exists($variable, $this->_globals))
        {
            return $this->_globals[$variable];
        }
        elseif(array_key_exists($variable, $this->_data))
        {
            return $this->_data[$variable];
        }

        return FALSE;
    }

    protected function get_config(Array $replace = array())
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

            // Is the config file in the environment folder?
            if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/twiggy.php'))
            {
                require($file_path);
            }
            elseif ( ! $found)
            {
                set_status_header(503);
                echo 'The configuration file does not exist.';
                exit(3); // EXIT_CONFIG
            }

            // Does the $config array exist in the file?
            if ( ! isset($config) OR ! is_array($config))
            {
                set_status_header(503);
                echo 'Your config file does not appear to be formatted correctly.';
                exit(3); // EXIT_CONFIG
            }
        }

        // Are any values being dynamically added or replaced?
        foreach ($replace as $key => $val)
        {
            $config[$key] = $val;
        }

        return $config['twiggy'];
    }

}
