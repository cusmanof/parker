<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_year_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'Year.Content.View',
			'description' => 'View Year Content',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Content.Create',
			'description' => 'Create Year Content',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Content.Edit',
			'description' => 'Edit Year Content',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Content.Delete',
			'description' => 'Delete Year Content',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Reports.View',
			'description' => 'View Year Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Reports.Create',
			'description' => 'Create Year Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Reports.Edit',
			'description' => 'Edit Year Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Reports.Delete',
			'description' => 'Delete Year Reports',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Settings.View',
			'description' => 'View Year Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Settings.Create',
			'description' => 'Create Year Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Settings.Edit',
			'description' => 'Edit Year Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Settings.Delete',
			'description' => 'Delete Year Settings',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Developer.View',
			'description' => 'View Year Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Developer.Create',
			'description' => 'Create Year Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Developer.Edit',
			'description' => 'Edit Year Developer',
			'status' => 'active',
		),
		array(
			'name' => 'Year.Developer.Delete',
			'description' => 'Delete Year Developer',
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