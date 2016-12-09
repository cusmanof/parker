<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Update_freedays extends Migration {

    /**
     * @var string The name of the database table
     */
    private $table_name = 'freedays';

    /**
     * @var array The table's fields
     */
    private $fields = array(
        'area' => array(
            'type' => 'VARCHAR',
            'constraint' => 64,
            'default' => '',
        ),
         'daycost' => array(
            'type' => 'DECIMAL',
            'default' => '0.0',
        ),
    );

    /**
     * Install this version
     *
     * @return void
     */
    public function up() {
        $this->dbforge->add_column($this->table_name, $this->fields);
    }

    /**
     * Uninstall this version
     *
     * @return void
     */
    public function down() {
        
    }

}
