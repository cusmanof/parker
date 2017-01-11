<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * About controller
 */
class About extends Front_Controller
{
    protected $permissionCreate = 'About.About.Create';
    protected $permissionDelete = 'About.About.Delete';
    protected $permissionEdit   = 'About.About.Edit';
    protected $permissionView   = 'About.About.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->lang->load('about');
        $this->load->helper('form');
        

        Assets::add_module_js('about', 'about.js');
    }

    /**
     * Display a list of About data.
     *
     * @return void
     */
    public function index()
    {   
        Template::render();
    }
    
}