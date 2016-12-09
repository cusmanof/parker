<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Settings controller
 */
class Settings extends Admin_Controller
{
    protected $permissionCreate = 'Locations.Settings.Create';
    protected $permissionDelete = 'Locations.Settings.Delete';
    protected $permissionEdit   = 'Locations.Settings.Edit';
    protected $permissionView   = 'Locations.Settings.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('locations/locations_model');
        $this->lang->load('locations');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'settings/_sub_nav');

        Assets::add_module_js('locations', 'locations.js');
    }

    /**
     * Display a list of locations data.
     *
     * @return void
     */
    public function index()
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked)) {

                // If any of the deletions fail, set the result to false, so
                // failure message is set if any of the attempts fail, not just
                // the last attempt

                $result = true;
                foreach ($checked as $pid) {
                    $deleted = $this->locations_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('locations_delete_success'), 'success');
                } else {
                    Template::set_message(lang('locations_delete_failure') . $this->locations_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->locations_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('locations_manage'));

        Template::render();
    }
    
    /**
     * Create a locations object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_locations()) {
                log_activity($this->auth->user_id(), lang('locations_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'locations');
                Template::set_message(lang('locations_create_success'), 'success');

                redirect(SITE_AREA . '/settings/locations');
            }

            // Not validation error
            if ( ! empty($this->locations_model->error)) {
                Template::set_message(lang('locations_create_failure') . $this->locations_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('locations_action_create'));

        Template::render();
    }
    /**
     * Allows editing of locations data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('locations_invalid_id'), 'error');

            redirect(SITE_AREA . '/settings/locations');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_locations('update', $id)) {
                log_activity($this->auth->user_id(), lang('locations_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'locations');
                Template::set_message(lang('locations_edit_success'), 'success');
                redirect(SITE_AREA . '/settings/locations');
            }

            // Not validation error
            if ( ! empty($this->locations_model->error)) {
                Template::set_message(lang('locations_edit_failure') . $this->locations_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->locations_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('locations_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'locations');
                Template::set_message(lang('locations_delete_success'), 'success');

                redirect(SITE_AREA . '/settings/locations');
            }

            Template::set_message(lang('locations_delete_failure') . $this->locations_model->error, 'error');
        }
        
        Template::set('locations', $this->locations_model->find($id));

        Template::set('toolbar_title', lang('locations_edit_heading'));
        Template::render();
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_locations($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->locations_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->locations_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->locations_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->locations_model->update($id, $data);
        }

        return $return;
    }
}