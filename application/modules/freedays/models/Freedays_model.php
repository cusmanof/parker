<?php defined('BASEPATH') || exit('No direct script access allowed');

class Freedays_model extends BF_Model
{
    protected $table_name	= 'freedays';
	protected $key			= 'id';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;


	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

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
	protected $validation_rules 		= array(
		array(
			'field' => 'owner',
			'label' => 'lang:freedays_field_owner',
			'rules' => 'required|trim|max_length[64]',
		),
		array(
			'field' => 'baylocation',
			'label' => 'lang:freedays_field_baylocation',
			'rules' => 'required|trim|max_length[64]',
		),
		array(
			'field' => 'datefree',
			'label' => 'lang:freedays_field_datefree',
                        'rules' => 'required',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
     function get_entries_for_owner($user, $yymm) {
        $result = array();
        $q = "SELECT * FROM freedays_tbl WHERE free_date LIKE '" . $yymm . "%' AND owner = '" . $user . "'";
        $query = $this->db->query($q);
        foreach ($query->result_array() as $row) {
            $dd = new DateTime($row['free_date']);
            if (empty($row['userId'])) {
                $result[$dd->format('j')] = "Free";
            } else {
                $result[$dd->format('j')] = $row['userId'];
            }
        }
        return $result;
    }

    function get_entries_for_month($user, $yymm) {
        $result = array();
//        $q = "SELECT * FROM bf_freedays WHERE datefree LIKE '" . $yymm . "%' AND area = ". $user->area;
        $query = $this->freedays_model->where('area', $user->area)->like('datefree',$yymm)->find_all();
        if ($query) foreach ($query as $row) {
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

    function get_requested_dates($yymm) {

        $result = array();
        $q = "SELECT * FROM freedays_tbl WHERE free_date LIKE '" . $yymm . "%' AND owner = '' and userId !=''";
        $query = $this->db->query($q);
        foreach ($query->result_array() as $row) {
            $dd = new DateTime($row['free_date']);
            $result[$dd->format('j')] = "Requested";
        }
        return $result;
    }

    function reserve_available_date($user, $yymmdd) {
        if (empty($user))
            return;
        //remove any request
        $q = "DELETE FROM freedays_tbl WHERE free_date = '" . $yymmdd . "' AND owner = '' AND userId= '" . $user . "'";
        $this->db->query($q);
        if ($this->db->affected_rows() <> 0) {
            return;
        }
        //remove if alloacted
        $q = "UPDATE freedays_tbl SET userId='' WHERE free_date = '" . $yymmdd . "' AND userId = '" . $user . "'";
        $this->db->query($q);
        if ($this->db->affected_rows() <> 0) {
            //released, see if anybody else wants this slot.
            $q = "SELECT * FROM freedays_tbl WHERE free_date = '" . $yymmdd . "' AND userId = '' AND owner <> '' LIMIT 1";
            $query = $this->db->query($q);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $owner = $row->owner;
                $bay = $row->parkId;
                //easiest way is to release/then make free for owner
                $this->do_release_for_owner($owner, $yymmdd);
                $this->do_free_for_owner($owner, $yymmdd, $bay);
            }
            return;
        }
        $q = "SELECT * FROM freedays_tbl WHERE userId = '" . $user . "'";
        $query = $this->db->query($q);
        if ($query->num_rows() >= 10) {
            $message = " Sorry, you cannot reserve more than 10 bays in advance." .
                    "Buy Frank a beer and he might increase your limit.";
            $this->session->set_flashdata('error', $message);
            return;
        }
        //allocate if one avail
        $q = "UPDATE freedays_tbl SET userId='" . $user
                . "' WHERE free_date = '" . $yymmdd
                . "' AND userId = '' ORDER BY parkId LIMIT 1";
        $this->db->query($q);
        if ($this->db->affected_rows() <> 0) {
            return;
        }
        //Ok reserve it
        $q = "INSERT INTO freedays_tbl (userId, free_date) VALUES ('$user','$yymmdd');";
        $this->db->query($q);
    }

    function do_release_for_owner($owner, $dd) {
        $q = "DELETE FROM freedays_tbl  WHERE free_date = '" . $dd . "' AND owner = '" . $owner . "' AND userId=''";
        $this->db->query($q);
    }

    function do_free_for_owner($owner, $yymmdd, $bay) {
        if (empty($owner))
            return;
        //see if somebody has requested that date
        $where = "WHERE  owner=''  and userId !='' and free_date LIKE '" . $yymmdd . "%' LIMIT 1";
        $q = "SELECT * from freedays_tbl " . $where;
        $res = $this->db->query($q);
        if ($this->db->affected_rows() != 0) {
            $q = "UPDATE freedays_tbl SET owner='" . $owner . "', parkId='" . $bay . "' " . $where;
            $this->db->query($q);
            $row = $row = $res->row();
            $this->email_gotOne($row->userId, $owner, $bay, $row->free_date);
        } else {
            $q = "INSERT INTO freedays_tbl (owner, parkId, free_date) VALUES ('$owner','$bay','$yymmdd');";
            $this->db->query($q);
        }
    }

    function do_reset($user, $isOwner) {
        if ($isOwner) {
            $q = "DELETE FROM freedays_tbl WHERE owner = '" . $user . "' AND userId=''";
        } else {
            $q = "UPDATE freedays_tbl SET userId='' WHERE  userId='" . $user . "'";
        }
        $this->db->query($q);
    }

    function do_list_all() {
        $tmpl = array('table_open' => '<table class="ftable">');
        $this->load->library('table');
        $this->table->set_template($tmpl);
        $q = "SELECT userId, parkId, free_date, owner FROM freedays_tbl WHERE userId<>'' ORDER BY free_date ";
        $query = $this->db->query($q);
        return $this->table->generate($query);
    }

    function do_list_user($user) {
        $res = array();
        $tmpl = array('table_open' => '<table class="ftable">');
        $this->load->library('table');
        $this->table->set_template($tmpl);
        $q = "SELECT userId, parkId, free_date, owner FROM freedays_tbl WHERE userId = '" . $user . "' ORDER BY  free_date";
        $query = $this->db->query($q);
        $h = array(
            "0" => 'Owner',
            "1" => 'Bay',
            "2" => 'Date',
            "3" => 'Phone',
            "4" => 'User'
        );
        array_push($res, $h);
        foreach ($query->result() as $row) {
            $oo = $row->owner;
            $ph = $this->ion_auth->getPhone($oo);
            if (!empty($oo)) {
                $email = $this->ion_auth->getEmail($oo);
                if (!empty($email)) {
                    $oo = mailto($email . '?subject= Re: parking bay: '
                            . $row->parkId . ' on ' . $row->free_date, $oo);
                }
            }
            $r = array(
                "0" => $row->userId,
                "1" => $row->parkId,
                "2" => $row->free_date,
                "3" => $ph,
                "4" => $oo
            );
            array_push($res, $r);
        }
        return $this->table->generate($res);
    }

    function do_list_free_days() {
        $res = array();
        $tmpl = array('table_open' => '<table class="ftable">');
        $this->load->library('table');
        $this->table->set_template($tmpl);
        $q = "SELECT DISTINCT free_date FROM freedays_tbl WHERE userId = '' ORDER BY free_date";
        $query = $this->db->query($q);
        $h = array(
            "0" => 'Date',
        );
        array_push($res, $h);
        foreach ($query->result() as $row) {
           
            $r = array(
                "0" => $row->free_date,          
            );
            array_push($res, $r);
        }
        return $this->table->generate($res);
    }
    
    function get_free_days() {
        $res = array();
        $q = "SELECT DISTINCT free_date FROM freedays_tbl WHERE userId = '' ORDER BY free_date ";
        $query = $this->db->query($q);
        
       foreach ($query->result() as $row) {
         array_push($res, $row->free_date);  
       }
        return $res;
    }
    
    function do_list_owner($user) {
        $res = array();
        $tmpl = array('table_open' => '<table class="ftable">');
        $this->load->library('table');
        $this->table->set_template($tmpl);
        $q = "SELECT owner, parkId, free_date, userId FROM freedays_tbl WHERE owner = '" . $user . "' ORDER BY free_date";
        $query = $this->db->query($q);
        $h = array(
            "0" => 'Owner',
            "1" => 'Bay',
            "2" => 'Date',
            "3" => 'Phone',
            "4" => 'User'
        );
        array_push($res, $h);
        foreach ($query->result() as $row) {
            $oo = $row->userId;
            $ph = $this->ion_auth->getPhone($oo);
            if (!empty($oo)) {
                $email = $this->ion_auth->getEmail($oo);
                if (!empty($email)) {
                    $oo = mailto($email . '?subject= Re: parking bay: '
                            . $row->parkId . ' on ' . $row->free_date, $oo);
                }
            }
            $r = array(
                "0" => $row->owner,
                "1" => $row->parkId,
                "2" => $row->free_date,
                "3" => $ph,
                "4" => $oo
            );
            array_push($res, $r);
        }
        return $this->table->generate($res);
    }

    function email_gotOne($dest, $owner, $bay, $date) {
        $email = $this->ion_auth->getEmail($dest);
        if (!empty($email)) {
            try {
                $message = new Message();
                $message->addTo($email);
                $message->setSender('cusmanof@gmail.com');
                $message->setSubject('Free Park allocation.');
                $message->setTextBody('Your request for a parking bay on '
                        . $date . ' have been filled by ' . $owner . '. Use bay ' . $bay);
                $message->send();
            } catch (Exception $e) {
                show_error('ERROR: ' . $e);
            }
        }
    }
}