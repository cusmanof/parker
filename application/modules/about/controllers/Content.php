<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'About.Content.Create';
    protected $permissionDelete = 'About.Content.Delete';
    protected $permissionEdit   = 'About.Content.Edit';
    protected $permissionView   = 'About.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('about');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('about', 'about.js');
    }

    /**
     * Display a list of About data.
     *
     * @return void
     */
    public function index()
    {
        
        
        
        
        
    Template::set('toolbar_title', lang('about_manage'));

        Template::render();
    }
    
    /**
     * Create a About object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        Template::set('toolbar_title', lang('about_action_create'));

        Template::render();
    }
    /**
     * Allows editing of About data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('about_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/about');
        }
        
        
        

        Template::set('toolbar_title', lang('about_edit_heading'));
        Template::render();
    }
}