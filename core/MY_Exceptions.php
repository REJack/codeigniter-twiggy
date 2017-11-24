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

class MY_Exceptions extends CI_Exceptions {

    protected $twiggy;

    public function __construct()
    {
        parent::__construct();
        $this->twiggy = $this->load_class('Twiggy');
    }

    public function show_404($page = '', $log_error = TRUE)
    {
        if (is_cli())
        {
            $this->twiggy->layout('errors/cli');
            $this->twiggy->template('errors/cli/error_404');
            $heading = 'Not Found';
            $message = 'The controller/method pair you requested was not found.';
        }
        else
        {
            $this->twiggy->layout('errors/html');
            $this->twiggy->template('errors/html/error_404');
            $heading = '404 Page Not Found';
            $message = '<p>The page you requested was not found.</p>';
        }
        if ($log_error)
        {
            log_message('error', $heading.': '.$page);
        }

        $this->twiggy->title($heading);
        $this->twiggy->set(array('message' => $message, 'heading' => $heading, 'page' => $page));
        echo $this->twiggy->display();
        exit(4);
    }

    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        if (is_cli())
        {
            $message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
            $this->twiggy->layout('errors/cli');
            $this->twiggy->template('errors/cli/'.$template);
        }
        else
        {
            set_status_header($status_code);
            $message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
            $this->twiggy->layout('errors/html');
            $this->twiggy->template('errors/html/'.$template);
        }
        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }

        ob_start();
        $this->twiggy->title($heading);
        $this->twiggy->set(array('message' => $message, 'heading' => $heading, 'status_code' => $status_code));
        echo $this->twiggy->display();
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    public function show_exception($exception)
    {
        $message = $exception->getMessage();
        $type = '';

        if (empty($message))
        {
            $message = '(NULL)';
        }
        if (is_cli())
        {
            $this->twiggy->layout('errors/cli');
            $type = 'cli';
        }
        else
        {
            $this->twiggy->layout('errors/html_blank');
            $type = 'html';
        }
        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }

        ob_start();
        $this->twiggy->template('errors/'.$type.'/error_exception');
        $this->twiggy->set(array('message' => $message, 'exception' => $exception));
        echo $this->twiggy->display();
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

    public function show_php_error($severity, $message, $filepath, $line)
    {
        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

        if (is_cli())
        {
            $this->twiggy->layout('errors/cli');
            $this->twiggy->template('errors/cli/error_php');
        }
        else
        {
            $this->twiggy->layout('errors/html_blank');
            $filepath = str_replace('\\', '/', $filepath);
            if (FALSE !== strpos($filepath, '/'))
            {
                $x = explode('/', $filepath);
                $filepath = $x[count($x) - 2].'/'.end($x);
            }
            $this->twiggy->template('errors/html/error_php');
        }
        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }

        ob_start();
        $this->twiggy->set(array('message' => $message, 'filepath' => $filepath, 'line' => $line, 'severity' => $severity));
        $this->twiggy->display();
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

    private function load_class($class, $directory = 'libraries', $param = NULL)
    {
        static $_classes = array();

        if (isset($_classes[$class]))
        {
            return $_classes[$class];
        }

        $file_found = FALSE;
        $name = FALSE;

        foreach (array(APPPATH, BASEPATH) as $path)
        {
            if (file_exists($path.$directory.'/'.$class.'.php'))
            {
                $name = $class;

                if (class_exists($name, FALSE) === FALSE)
                {
                    require_once $path.$directory.'/'.$class.'.php';
                    $file_found = TRUE;
                }

                break;
            }
        }

        if ($name === FALSE)
        {
            set_status_header(503);
            echo 'Unable to locate the specified class: '.$class.'.php';
            exit(5); // EXIT_UNK_CLASS
        }

        is_loaded($class);
        $_classes[$class] = isset($param) ? new $name($param) : new $name();
        return $_classes[$class];
    }
}
