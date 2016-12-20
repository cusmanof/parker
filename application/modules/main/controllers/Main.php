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

    public function do_owner_select() {
        $dd = $this->icheck($this->input->get('day'), '');
        if (!empty($dd) && $dd >= date('Y-m-d')) {
            if ($this->session->has_userdata('first_day')) {
                $this->session->set_userdata('last_day', $dd);
            } else {
                $this->session->set_userdata('first_day', $dd);
            }
        }
    }

    public function do_user_select() {
        $dd = $this->icheck($this->input->get('day'), '');
        if (!empty($dd) && $dd >= date('Y-m-d')) {
            $this->freedays_model->alloc($this->user_data, $dd);
        }
    }

    private function icheck($val, $default) {
        return isset($val) ? $val : $default;
    }

    public function index() {
        $result = array();
        $used = array();
        $free = array();
        array_push($result, $used, $free);
        $this->act = $this->icheck($this->input->get('act'), 'move');
        $this->yy = $this->icheck($this->input->get('year'), date('Y'));
        $this->mm = $this->icheck($this->input->get('month'), date('m'));
        $curr_href = '&month=' . sprintf('%02d', $this->mm) . '&year=' . $this->yy;
        switch ($this->act) {
            case 'select':
                if ($this->isOwner) {
                    $this->do_owner_select();
                } else {
                    $this->do_user_select();
                }
                break;
            case 'clear':
                $this->session->unset_userdata('first_day');
                $this->session->unset_userdata('last_day');
                redirect($this->session->previous_page . '?act=move' . $curr_href);
                break;
            case 'free':
                $f = $this->session->first_day;
                $e = $this->session->last_day;
                if (empty($e))
                    $e = $f;
                if (!empty($f)) { //safety first
                    $this->first = min($f, $e);
                    $this->last = max($f, $e);
                    $this->freedays_model->free_up($this->user_data, $f, $e);
                }
                $this->session->unset_userdata('first_day');
                $this->session->unset_userdata('last_day');
                redirect($this->session->previous_page . '?act=move' . $curr_href);
                break;
            case 'recall':
                $f = $this->session->first_day;
                $e = $this->session->last_day;
                if (empty($e))
                    $e = $f;
                if (!empty($f)) { //safety first
                    $this->first = min($f, $e);
                    $this->last = max($f, $e);
                    $this->freedays_model->unfree_up($this->user_data, $f, $e);
                }
                $this->session->unset_userdata('first_day');
                $this->session->unset_userdata('last_day');
                redirect($this->session->previous_page . '?act=move' . $curr_href);
                break;
            default:
                break;
        }

        if ($this->isOwner) {
            if ($this->session->has_userdata('first_day')) {
                $result['first_day'] = $this->session->first_day;
                if ($this->session->has_userdata('last_day')) {
                    $result['last_day'] = $this->session->last_day;
                }
                $this->session->set_flashdata('msg', 'Press the button you require.');
            } else {
                $this->session->set_flashdata('msg', 'Click on the first and then the last day you want to free up.');
            }
            $result['used'] = $this->freedays_model->get_used($this->user_data);
            $result['free'] = $this->freedays_model->get_free($this->user_data);
        } else {
            $result['free'] = $this->freedays_model->get_unalloc($this->user_data);
            $result['used'] = $this->freedays_model->get_reserved($this->user_data);
            $this->session->set_flashdata('msg', 'Click on a date to reserve that day');
        }

        $data = array(
            'year' => $this->yy,
            'month' => $this->mm,
            'content' => $result,
        );
        $data['user_model'] = $this->user_model;
// Load view page
        if ($this->isOwner) {
            $data['user'] = "Owner : " . $this->user_data->username . ' @ ' . $this->user_data->area;
            $data['isUser'] = false;
        } else {
            $data['user'] = "User : " . $this->user_data->username . ' @ ' . $this->user_data->area;
            $data['isUser'] = true;
        }
        Assets::add_module_css('main', 'falendar.css');
        Template::set('data', $data);
        Template::render();
    }

}
