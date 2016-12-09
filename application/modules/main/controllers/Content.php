<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Main.Content.Create';
    protected $permissionDelete = 'Main.Content.Delete';
    protected $permissionEdit   = 'Main.Content.Edit';
    protected $permissionView   = 'Main.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('main');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('main', 'main.js');
    }

    /**
     * Display a list of main data.
     *
     * @return void
     */
    public function index()
    {
        
        
        
        
        
    Template::set('toolbar_title', lang('main_manage'));

        Template::render();
    }
    
    /**
     * Create a main object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        Template::set('toolbar_title', lang('main_action_create'));

        Template::render();
    }
    /**
     * Allows editing of main data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('main_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/main');
        }
        
        
        

        Template::set('toolbar_title', lang('main_edit_heading'));
        Template::render();
    }
}