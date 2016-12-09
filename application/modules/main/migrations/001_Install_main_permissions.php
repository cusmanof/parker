<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_main_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'Main.Main.View',
			'description' => 'View Main Main',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Main.Create',
			'description' => 'Create Main Main',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Main.Edit',
			'description' => 'Edit Main Main',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Main.Delete',
			'description' => 'Delete Main Main',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Content.View',
			'description' => 'View Main Content',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Content.Create',
			'description' => 'Create Main Content',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Content.Edit',
			'description' => 'Edit Main Content',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Content.Delete',
			'description' => 'Delete Main Content',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Reports.View',
			'description' => 'View Main Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Reports.Create',
			'description' => 'Create Main Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Reports.Edit',
			'description' => 'Edit Main Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Reports.Delete',
			'description' => 'Delete Main Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Settings.View',
			'description' => 'View Main Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Settings.Create',
			'description' => 'Create Main Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Settings.Edit',
			'description' => 'Edit Main Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Settings.Delete',
			'description' => 'Delete Main Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Developer.View',
			'description' => 'View Main Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Developer.Create',
			'description' => 'Create Main Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Developer.Edit',
			'description' => 'Edit Main Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Main.Developer.Delete',
			'description' => 'Delete Main Developer',
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