<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Main controller
 */
class Main extends Front_Controller {

    protected $permissionCreate = 'Main.Main.Create';
    protected $permissionDelete = 'Main.Main.Delete';
    protected $permissionEdit = 'Main.Main.Edit';
    protected $permissionView = 'Main.Main.View';
    var $yy;
    var $mm;
    var $dd;
    var $ryy;
    var $rmm;
    var $rdd;
    var $range;
    var $user_data;
    var $bay;
    var $isOwner;
    var $act;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->lang->load('main');
        $this->load->model('users/user_model');
        $this->user_data = $this->user_model->find_user_and_meta($this->current_user->id);
        $this->isOwner = $this->user_data->type == 'owner' && $this->user_data->parklocation != '';
        $this->load->library('falendar');
        $this->load->library('session');
        Assets::add_module_js('main', 'main.js');
    }

    private function T2($vv) {
        return substr('00' . $vv, -2);
    }

    public function fullDate() {
        return $this->yy . "-" . $this->T2($this->mm) . "-" . $this->T2($this->dd);
    }

    public function partDate() {
        return $this->yy . "-" . $this->T2($this->mm) . "-";
    }

    public function do_user() {
        $this->freedays->reserve_available_date($this->user_data, $this->fullDate());
    }

    public function all() {


        if ($this->isOwner) {
            $data['user'] = "Owner : " . $this->user_data->username;
            $data['isUser'] = false;
            $data["table"] = $this->freedays_model->do_list_owner($this->user_data);
        } else {
            $data['user'] = "User : " . $this->user_data->username;
            $data['isUser'] = true;
            $data["table"] = $this->freedays_model->do_list_user($this->user_data);
        }

        Assets::add_module_css('main', 'falendar.css');
        Template::set('data', $data);
        Template::render();
    }

    public function do_owner1() {
        $result = $this->freedays_model->get_entries_for_owner($this->user_data, $this->partDate());
        if (array_key_exists($this->dd, $result)) {
            $this->freedays_model->do_release_for_owner($this->user_data, $this->fullDate());
        } else {
            $this->freedays_model->do_free_for_owner($this->user_data, $this->fullDate(), $this->bay);
        }
    }

    public function do_owner() {
        $this->do_owner1();
        if ($this->range) {
            $f = 'Y-m-d';
            $s = $this->yy . "-" . $this->mm . "-" . $this->dd;
            $e = $this->ryy . "-" . $this->rmm . "-" . $this->rdd;
            $endDate = date_create_from_format($f, $e);
            $workDate = date_create_from_format($f, $s);
            while ($workDate < $endDate) {
                $workDate->modify('+1 day');
                $this->yy = $workDate->format('Y');
                $this->mm = $workDate->format('m');
                $this->dd = $workDate->format('d');
                $this->do_owner1();
            }
        }
    }

    public function reset() {
        $this->freedays_model->do_reset($this->user, $isOwner);
        redirect('home');
    }

    private function icheck($val, $default) {
        return isset($val) ? $val : $default;
    }

    public function index() {
        $this->act = $this->icheck($this->input->get('act'), 'move');
        switch ($this->act) {
            case 'single':
                $dd = $this->icheck($this->input->get('day'), '');
                if (!empty($dd) && $dd >= date('Y-m-d')) {
                    if ($this->session->has_userdata('first_day')) {
                        $d1 = $this->session->first_day;
                       var_dump($dd); 
                       $this->session->unset_userdata('first_day');
                    }else {
                     $this->session->set_userdata('first_day', $dd); 
                      return;
                    }
                }
               break;
            default:
                $this->session->unset_userdata('first_day');
                $this->yy = $this->icheck($this->input->get('year'), date('Y'));
                $this->mm = $this->icheck($this->input->get('month'), date('m'));
        }

        $result = array();

        $data = array(
            'year' => $this->yy,
            'month' => $this->mm,
            'content' => $result
        );

// Load view page
        if ($this->isOwner) {
            $data['user'] = "Owner : " . $this->user_data->username;
            $data['isUser'] = false;
        } else {
            $data['user'] = "User : " . $this->user_data->username;
            $data['isUser'] = true;
        }
        Assets::add_module_css('main', 'falendar.css');
        Template::set('data', $data);
        Template::render();
    }

}
