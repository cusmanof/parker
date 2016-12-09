<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Locations controller
 */
class Locations extends Front_Controller
{
    protected $permissionCreate = 'Locations.Locations.Create';
    protected $permissionDelete = 'Locations.Locations.Delete';
    protected $permissionEdit   = 'Locations.Locations.Edit';
    protected $permissionView   = 'Locations.Locations.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('locations/locations_model');
        $this->lang->load('locations');
        
        

        Assets::add_module_js('locations', 'locations.js');
    }

    /**
     * Display a list of locations data.
     *
     * @return void
     */
    public function index()
    {
        
        
        
        
        $records = $this->locations_model->find_all();

        Template::set('records', $records);
        

        Template::render();
    }
    
}