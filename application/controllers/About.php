<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class About extends MX_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('application');
        $this->load->library('Template');
        $this->load->library('Assets');
        $this->lang->load('application');
        $this->load->library('events');

       
        // Make the requested page var available, since
        // we're not extending from a Bonfire controller
        // and it's not done for us.
        $this->requested_page = isset($_SESSION['requested_page']) ? $_SESSION['requested_page'] : null;
    }

    //--------------------------------------------------------------------

    /**
     * Displays the homepage of the Bonfire app
     *
     * @return void
     */
    public function index() {
        
        Template::set('about');
        Template::render();
    }
}