<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Freedays controller
 */
class Freedays extends Front_Controller
{
    protected $permissionCreate = 'Freedays.Freedays.Create';
    protected $permissionDelete = 'Freedays.Freedays.Delete';
    protected $permissionEdit   = 'Freedays.Freedays.Edit';
    protected $permissionView   = 'Freedays.Freedays.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
       
        
        $this->load->model('freedays/freedays_model');
        $this->lang->load('freedays');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
        

        Assets::add_module_js('freedays', 'freedays.js');
    }

    /**
     * Display a list of freedays data.
     *
     * @return void
     */
    public function index()
    {
        
        $this->load->library('users/auth');
        $this->auth->restrict('Bonfire.Roles.View'); 
        
        
        $records = $this->freedays_model->find_all();

        Template::set('records', $records);
        

        Template::render();
    }
    
}