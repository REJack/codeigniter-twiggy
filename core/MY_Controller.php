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

class MY_Controller extends CI_Controller {

    public function __destruct()
    {
        $routed_segments = $this->uri->rsegment_array();
        $this->twiggy->display(implode('/', $routed_segments));
    }
}
