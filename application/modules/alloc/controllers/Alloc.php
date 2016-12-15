<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Alloc controller
 */
class Alloc extends Front_Controller
{
    protected $permissionCreate = 'Alloc.Alloc.Create';
    protected $permissionDelete = 'Alloc.Alloc.Delete';
    protected $permissionEdit   = 'Alloc.Alloc.Edit';
    protected $permissionView   = 'Alloc.Alloc.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->lang->load('alloc');
                $this->load->model('users/user_model');
        $this->user_data = $this->user_model->find_user_and_meta($this->current_user->id);
        $this->isOwner = $this->user_data->type == 'owner' && $this->user_data->parklocation != '';

        

        Assets::add_module_js('alloc', 'alloc.js');
    }

    /**
     * Display a list of alloc data.
     *
     * @return void
     */
    public function index()
    {
        
    $data =$this->freedays_model->get_alloc($this->user_data);  
        
        
     Template::set('data', $data);        
 
        Template::render();
    }
    
}