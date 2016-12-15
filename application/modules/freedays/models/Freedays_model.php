<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Freedays_model extends BF_Model {

    protected $table_name = 'freedays';
    protected $key = 'id';
    protected $date_format = 'datetime';
    protected $log_user = false;
    protected $set_created = false;
    protected $set_modified = false;
    protected $soft_deletes = false;
    // Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
    protected $before_insert = array();
    protected $after_insert = array();
    protected $before_update = array();
    protected $after_update = array();
    protected $before_find = array();
    protected $after_find = array();
    protected $before_delete = array();
    protected $after_delete = array();
    // For performance reasons, you may require your model to NOT return the id
    // of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
    protected $return_insert_id = true;
    // The default type for returned row data.
    protected $return_type = 'object';
    // Items that are always removed from data prior to inserts or updates.
    protected $protected_attributes = array();
    // You may need to move certain rules (like required) into the
    // $insert_validation_rules array and out of the standard validation array.
    // That way it is only required during inserts, not updates which may only
    // be updating a portion of the data.
    protected $validation_rules = array();
    protected $insert_validation_rules = array();
    protected $skip_validation = true;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    function get_entries_for_month($user, $yymm) {
        $result = array();
        $query = $this->freedays_model->where('area', $user->area)->like('datefree', $yymm)->find_all();
        if ($query)
            foreach ($query as $row) {
                $dd = new DateTime($row->datefree);
                if (empty($row->userId) && !isset($result[$dd->format('j')])) {
                    $result[$dd->format('j')] = '<span style="color:green"><B></I>Free</B></I></span>';
                } else if ($user->username == $row->user) {
                    if (!empty($row->owner)) {
                        $res = 'park in <span style="color:#0000DD">' . $row->baylocation . '</span><BR>';
                        $res = $res . $row['owner'];
                    } else {
                        $res = '<span style="color:#D2691E">Requested</span>';
                    }
                    $result[$dd->format('j')] = $res;
                }
            }
        return $result;
    }

    function free_up($user, $f, $e) {
        //first delete any entries within that range as long as they are not already taken.
        $this->unfree_up($user, $f, $e);
        while (strtotime($f) <= strtotime($e)) {
            $w2 = array(
                'owner' => $user->username,
                'area' => $user->area,
                'datefree' => $f
            );
            if ($this->freedays_model->count_by($w2) == 0) {
                $w3 = array(
                    'owner' => $user->username,
                    'area' => $user->area,
                    'user' => '',
                    'baylocation' => $user->parklocation,
                    'datefree' => $f
                );
                $this->freedays_model->insert($w3);
            }
            $f = date("Y-m-d", strtotime("+1 days", strtotime($f)));
        }
    }

    function unfree_up($user, $f, $e) {
        //first delete any entries within that range as long as they are not already taken.
        $this->freedays_model
                ->delete_where(
                        'owner=\'' . $user->username
                        . '\' AND area=\'' . $user->area
                        . '\' AND user=\''
                        . '\' AND datefree>=\'' . $f
                        . '\' AND datefree<=\'' . $e . '\'');
    }

    function get_used($user1) {
        $arr = array();
        $query = $this->freedays_model
                ->where('owner', $user1->username)
                ->where('area', $user1->area)
                ->find_all();
        if ($query) {
            foreach ($query as $row) {
                if (!empty($row->user)) {
                    $arr[$row->datefree]= $row->user;
                }
            }
        }
        return $arr;
    }

    function get_free($user1) {
        $arr = array();
        $query = $this->freedays_model
                ->where('owner', $user1->username)
                ->where('area', $user1->area)
                ->where('user', '')
                ->find_all();
        if ($query) {
            foreach ($query as $row) {
                array_push($arr, $row->datefree);
            }
        }
        return $arr;
    }
    function get_alloc($user1) {
        $query = $this->freedays_model
                ->where('owner', $user1->username)
                ->where('area', $user1->area)
                ->join('users', 'users.username = user' )
                ->find_all();
        if (!$query) {
           return null;
        }
        return $query;
    }
}
