<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Year controller
 */
class Year extends Front_Controller {

    protected $permissionCreate = 'Year.Year.Create';
    protected $permissionDelete = 'Year.Year.Delete';
    protected $permissionEdit = 'Year.Year.Edit';
    protected $permissionView = 'Year.Year.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->lang->load('year');
        $this->load->model('users/user_model');

        Assets::add_module_js('year', 'year.js');
    }

    /**
     * Display a list of year data.
     *
     * @return void
     */
    public function index() {
         $user = $this->user_model->find_user_and_meta($this->current_user->id);
        $flds = $this->freedays_model->select('datefree')->find_all_by(array('user'=>'','area'=> $user->area));
        $data = array();
        if ($flds) {
            foreach ($flds as $free) :
                array_push($data, $free->datefree);
            endforeach;
        }
        $data['area'] = $user->area;
        Template::set('data', $data);
        Template::render();
    }

}
