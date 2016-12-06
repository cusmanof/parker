<?php

defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */
//------------------------------------------------------------------------------
// User Meta Fields Config - These are just examples of various options
// The following examples show how to use regular inputs, select boxes,
// state and country select boxes.
//------------------------------------------------------------------------------

$config['user_meta_fields'] = array(
    array(
        'name'   => 'type',
        'label'   => 'Car park',
        'rules'   => 'required',
        'form_detail' => array(
            'type' => 'dropdown',
            'settings' => array(
                'name'      => 'type',
                'id'        => 'type',
                'class'     => 'span2',
            ),
            'options' =>  array(
                'owner'  => 'OWNER',
                'user'    => 'USER',
              ),
        ),
    ),
    array(
        'name' => 'phone',
        'label' => 'Phone',
        'rules' => 'trim|max_length[24]',
        'form_detail' => array(
            'type' => 'input',
            'settings' => array(
                'name' => 'phone',
                'id' => 'phone',
                'maxlength' => '24',
                'class' => 'span3'
            ),
        ),
    ),  
    array(
        'name' => 'area',
        'label' => 'Park location',
        'rules' => 'trim|max_length[24]',
        'form_detail' => array(
            'type' => 'input',
            'settings' => array(
                'name' => 'area',
                'id' => 'area',
                'maxlength' => '64',
                'class' => 'span6',
                'default' => 'West Leederville'
            ),
        ),
    ),
     array(
        'name' => 'parklocation',
        'label' => 'Bay number',
        'rules' => 'trim|max_length[24]',
        'form_detail' => array(
            'type' => 'input',
            'settings' => array(
                'name' => 'parklocation',
                'id' => 'parklocation',
                'maxlength' => '24',
                'class' => 'span3'
            ),
        ),
    ),
);
