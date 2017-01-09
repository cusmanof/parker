<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_about_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'About.Main.View',
			'description' => 'View About Main',
			'status' => 'active',
		),
		array(
			'name' => 'About.Main.Create',
			'description' => 'Create About Main',
			'status' => 'active',
		),
		array(
			'name' => 'About.Main.Edit',
			'description' => 'Edit About Main',
			'status' => 'active',
		),
		array(
			'name' => 'About.Main.Delete',
			'description' => 'Delete About Main',
			'status' => 'active',
		),
		array(
			'name' => 'About.Content.View',
			'description' => 'View About Content',
			'status' => 'active',
		),
		array(
			'name' => 'About.Content.Create',
			'description' => 'Create About Content',
			'status' => 'active',
		),
		array(
			'name' => 'About.Content.Edit',
			'description' => 'Edit About Content',
			'status' => 'active',
		),
		array(
			'name' => 'About.Content.Delete',
			'description' => 'Delete About Content',
			'status' => 'active',
		),
		array(
			'name' => 'About.Reports.View',
			'description' => 'View About Reports',
			'status' => 'active',
		),
		array(
			'name' => 'About.Reports.Create',
			'description' => 'Create About Reports',
			'status' => 'active',
		),
		array(
			'name' => 'About.Reports.Edit',
			'description' => 'Edit About Reports',
			'status' => 'active',
		),
		array(
			'name' => 'About.Reports.Delete',
			'description' => 'Delete About Reports',
			'status' => 'active',
		),
		array(
			'name' => 'About.Settings.View',
			'description' => 'View About Settings',
			'status' => 'active',
		),
		array(
			'name' => 'About.Settings.Create',
			'description' => 'Create About Settings',
			'status' => 'active',
		),
		array(
			'name' => 'About.Settings.Edit',
			'description' => 'Edit About Settings',
			'status' => 'active',
		),
		array(
			'name' => 'About.Settings.Delete',
			'description' => 'Delete About Settings',
			'status' => 'active',
		),
		array(
			'name' => 'About.Developer.View',
			'description' => 'View About Developer',
			'status' => 'active',
		),
		array(
			'name' => 'About.Developer.Create',
			'description' => 'Create About Developer',
			'status' => 'active',
		),
		array(
			'name' => 'About.Developer.Edit',
			'description' => 'Edit About Developer',
			'status' => 'active',
		),
		array(
			'name' => 'About.Developer.Delete',
			'description' => 'Delete About Developer',
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