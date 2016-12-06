<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Developer controller
 */
class Developer extends Admin_Controller
{
    protected $permissionCreate = 'Year.Developer.Create';
    protected $permissionDelete = 'Year.Developer.Delete';
    protected $permissionEdit   = 'Year.Developer.Edit';
    protected $permissionView   = 'Year.Developer.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('year');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'developer/_sub_nav');

        Assets::add_module_js('year', 'year.js');
    }

    /**
     * Display a list of year data.
     *
     * @return void
     */
    public function index()
    {
        
        
        
        
        
    Template::set('toolbar_title', lang('year_manage'));

        Template::render();
    }
    
    /**
     * Create a year object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        Template::set('toolbar_title', lang('year_action_create'));

        Template::render();
    }
    /**
     * Allows editing of year data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('year_invalid_id'), 'error');

            redirect(SITE_AREA . '/developer/year');
        }
        
        
        

        Template::set('toolbar_title', lang('year_edit_heading'));
        Template::render();
    }
}