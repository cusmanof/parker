<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_locations_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'Locations.Main.View',
			'description' => 'View Locations Main',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Main.Create',
			'description' => 'Create Locations Main',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Main.Edit',
			'description' => 'Edit Locations Main',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Main.Delete',
			'description' => 'Delete Locations Main',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Content.View',
			'description' => 'View Locations Content',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Content.Create',
			'description' => 'Create Locations Content',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Content.Edit',
			'description' => 'Edit Locations Content',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Content.Delete',
			'description' => 'Delete Locations Content',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Reports.View',
			'description' => 'View Locations Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Reports.Create',
			'description' => 'Create Locations Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Reports.Edit',
			'description' => 'Edit Locations Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Reports.Delete',
			'description' => 'Delete Locations Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Settings.View',
			'description' => 'View Locations Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Settings.Create',
			'description' => 'Create Locations Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Settings.Edit',
			'description' => 'Edit Locations Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Settings.Delete',
			'description' => 'Delete Locations Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Developer.View',
			'description' => 'View Locations Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Developer.Create',
			'description' => 'Create Locations Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Developer.Edit',
			'description' => 'Edit Locations Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Locations.Developer.Delete',
			'description' => 'Delete Locations Developer',
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