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
        

        Assets::add_module_js('year', 'year.js');
    }

    /**
     * Display a list of year data.
     *
     * @return void
     */
    public function index() {
      
        $flds = $this->freedays_model->select('datefree')->where('user', '')->find_all();
        $data = array();
        foreach ($flds as $free) :
            array_push($data, $free->datefree);
        endforeach;
        Template::set('data', $data);
        Template::render();
    }

}
