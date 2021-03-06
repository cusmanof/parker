<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Alloc.Content.Create';
    protected $permissionDelete = 'Alloc.Content.Delete';
    protected $permissionEdit   = 'Alloc.Content.Edit';
    protected $permissionView   = 'Alloc.Content.View';

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
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('alloc', 'alloc.js');
    }

    /**
     * Display a list of alloc data.
     *
     * @return void
     */
    public function index()
    {
        
        
        
        
        
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

            redirect(SITE_AREA . '/content/alloc');
        }
        
        
        

        Template::set('toolbar_title', lang('alloc_edit_heading'));
        Template::render();
    }
}