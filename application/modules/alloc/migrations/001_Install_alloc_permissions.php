<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_alloc_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'Alloc.Main.View',
			'description' => 'View Alloc Main',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Main.Create',
			'description' => 'Create Alloc Main',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Main.Edit',
			'description' => 'Edit Alloc Main',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Main.Delete',
			'description' => 'Delete Alloc Main',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Content.View',
			'description' => 'View Alloc Content',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Content.Create',
			'description' => 'Create Alloc Content',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Content.Edit',
			'description' => 'Edit Alloc Content',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Content.Delete',
			'description' => 'Delete Alloc Content',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Reports.View',
			'description' => 'View Alloc Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Reports.Create',
			'description' => 'Create Alloc Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Reports.Edit',
			'description' => 'Edit Alloc Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Reports.Delete',
			'description' => 'Delete Alloc Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Settings.View',
			'description' => 'View Alloc Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Settings.Create',
			'description' => 'Create Alloc Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Settings.Edit',
			'description' => 'Edit Alloc Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Settings.Delete',
			'description' => 'Delete Alloc Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Developer.View',
			'description' => 'View Alloc Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Developer.Create',
			'description' => 'Create Alloc Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Developer.Edit',
			'description' => 'Edit Alloc Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Alloc.Developer.Delete',
			'description' => 'Delete Alloc Developer',
			'status' => 'active',
		),
    );

    /**
     * @var string The name of the permission key in the role_permissions table
     */
    private $permissionKey = 'permission_id';

    /**
     * @var string The name of the permission name field in the permissions table
     */
    private $permissionNameField = 'name';

	/**
	 * @var string The name of the role/permissions ref table
	 */
	private $rolePermissionsTable = 'role_permissions';

    /**
     * @var numeric The role id to which the permissions will be applied
     */
    private $roleId = '1';

    /**
     * @var string The name of the role key in the role_permissions table
     */
    private $roleKey = 'role_id';

	/**
	 * @var string The name of the permissions table
	 */
	private $tableName = 'permissions';

	//--------------------------------------------------------------------

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		$rolePermissionsData = array();
		foreach ($this->permissionValues as $permissionValue) {
			$this->db->insert($this->tableName, $permissionValue);

			$rolePermissionsData[] = array(
                $this->roleKey       => $this->roleId,
                $this->permissionKey => $this->db->insert_id(),
			);
		}

		$this->db->insert_batch($this->rolePermissionsTable, $rolePermissionsData);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
        $permissionNames = array();
		foreach ($this->permissionValues as $permissionValue) {
            $permissionNames[] = $permissionValue[$this->permissionNameField];
        }

        $query = $this->db->select($this->permissionKey)
                          ->where_in($this->permissionNameField, $permissionNames)
                          ->get($this->tableName);

        if ( ! $query->num_rows()) {
            return;
        }

        $permissionIds = array();
        foreach ($query->result() as $row) {
            $permissionIds[] = $row->{$this->permissionKey};
        }

        $this->db->where_in($this->permissionKey, $permissionIds)
                 ->delete($this->rolePermissionsTable);

        $this->db->where_in($this->permissionNameField, $permissionNames)
                 ->delete($this->tableName);
	}
}