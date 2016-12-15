<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Main controller
 */
class Main extends Admin_Controller
{
    protected $permissionCreate = 'Alloc.Main.Create';
    protected $permissionDelete = 'Alloc.Main.Delete';
    protected $permissionEdit   = 'Alloc.Main.Edit';
    protected $permissionView   = 'Alloc.Main.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('alloc');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'main/_sub_nav');

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
    Template::set('toolbar_title', lang('alloc_manage'));

        Template::render();
    }
    
    /**
     * Create a alloc object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        Template::set('toolbar_title', lang('alloc_action_create'));

        Template::render();
    }
    /**
     * Allows editing of alloc data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('alloc_invalid_id'), 'error');

            redirect(SITE_AREA . '/main/alloc');
        }
        
        
        

        Template::set('toolbar_title', lang('alloc_edit_heading'));
        Template::render();
    }
}