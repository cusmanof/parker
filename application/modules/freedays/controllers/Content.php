<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Freedays.Content.Create';
    protected $permissionDelete = 'Freedays.Content.Delete';
    protected $permissionEdit   = 'Freedays.Content.Edit';
    protected $permissionView   = 'Freedays.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('freedays/freedays_model');
        $this->lang->load('freedays');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('freedays', 'freedays.js');
    }

    /**
     * Display a list of freedays data.
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
                    $deleted = $this->freedays_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('freedays_delete_success'), 'success');
                } else {
                    Template::set_message(lang('freedays_delete_failure') . $this->freedays_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->freedays_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('freedays_manage'));

        Template::render();
    }
    
    /**
     * Create a freedays object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_freedays()) {
                log_activity($this->auth->user_id(), lang('freedays_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'freedays');
                Template::set_message(lang('freedays_create_success'), 'success');

                redirect(SITE_AREA . '/content/freedays');
            }

            // Not validation error
            if ( ! empty($this->freedays_model->error)) {
                Template::set_message(lang('freedays_create_failure') . $this->freedays_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('freedays_action_create'));

        Template::render();
    }
    /**
     * Allows editing of freedays data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('freedays_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/freedays');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_freedays('update', $id)) {
                log_activity($this->auth->user_id(), lang('freedays_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'freedays');
                Template::set_message(lang('freedays_edit_success'), 'success');
                redirect(SITE_AREA . '/content/freedays');
            }

            // Not validation error
            if ( ! empty($this->freedays_model->error)) {
                Template::set_message(lang('freedays_edit_failure') . $this->freedays_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->freedays_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('freedays_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'freedays');
                Template::set_message(lang('freedays_delete_success'), 'success');

                redirect(SITE_AREA . '/content/freedays');
            }

            Template::set_message(lang('freedays_delete_failure') . $this->freedays_model->error, 'error');
        }
        
        Template::set('freedays', $this->freedays_model->find($id));

        Template::set('toolbar_title', lang('freedays_edit_heading'));
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
    private function save_freedays($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->freedays_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->freedays_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        
		$data['datefree']	= $this->input->post('datefree') ? $this->input->post('datefree') : '0000-00-00';

        $return = false;
        if ($type == 'insert') {
            $id = $this->freedays_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->freedays_model->update($id, $data);
        }

        return $return;
    }
}