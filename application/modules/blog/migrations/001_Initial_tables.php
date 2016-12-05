<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Initial_tables extends Migration
{
    private $permissionValues = array(
        array('name' => 'Bonfire.Blog.View', 'description' => 'View the blog menu.', 'status' => 'active'),
        array('name' => 'Bonfire.Blog.Delete', 'description' => 'Delete blog entries', 'status' => 'active'),
    );

    private $permittedRoles = array(
        'Administrator',
    );

    /**
     * The definition(s) for the table(s) used by this migration.
     * @type array
     */
    private $tables = array(
        'posts' => array(
            'primaryKey' => 'post_id',
            'fields' => array(
                'post_id' => array(
                    'type'           => 'bigint',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ),
                'title' => array(
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => false,
                ),
                'slug' => array(
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => false,
                ),
                'body' => array(
                    'type' => 'text',
                    'null' => true,
                ),
                'created_on' => array(
                    'type' => 'datetime',
                    'null' => false,
                ),
                'modified_on' => array(
                    'type'    => 'datetime',
                    'null'    => true,
                    'default' => '0000-00-00 00:00:00',
                ),
                'deleted' => array(
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ),
            ),
        ),
    );

    /**
     * Install the blog tables
     *
     * @return void
     */
    public function up()
    {
        $this->load->dbforge();

        // Install the table(s) in the database.
        foreach ($this->tables as $tableName => $tableDef) {
            $this->dbforge->add_field($tableDef['fields']);
            $this->dbforge->add_key($tableDef['primaryKey'], true);
            $this->dbforge->create_table($tableName);
        }

        // Create the Permissions.
        $this->load->model('permissions/permission_model');
        $permissionNames = array();
        foreach ($this->permissionValues as $permissionValue) {
            $this->permission_model->insert($permissionValue);
            $permissionNames[] = $permissionValue['name'];
        }

        // Assign them to the permitted roles.
        $this->load->model('role_permission_model');
        foreach ($this->permittedRoles as $permittedRole) {
            foreach ($permissionNames as $permissionName) {
                $this->role_permission_model->assign_to_role($permittedRole, $permissionName);
            }
        }
    }

    /**
     * Remove the blog tables
     *
     * @return void
     */
    public function down()
    {
        // Remove the data.
        $this->load->dbforge();
        foreach ($this->tables as $tableName => $tableDef) {
            $this->dbforge->drop_table($tableName);
        }

        // Remove the permissions.
        $this->load->model('roles/role_permission_model');
        $this->load->model('permissions/permission_model');

        $permissionKey = $this->permission_model->get_key();
        foreach ($this->permissionValues as $permissionValue) {
            $permission = $this->permission_model->select($permissionKey)
                                                 ->find_by('name', $permissionValue['name']);
            if ($permission) {
                // permission_model's delete method calls the role_permission_model's
                // delete_for_permission method.
                $this->permission_model->delete($permission->{$permissionKey});
            }
        }
    }
}