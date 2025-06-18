<?php namespace Kodeine\Acl\Tests\Models;

use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PermissionTest extends ModelsTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Permission */
    protected $permissionModel;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->permissionModel = new Permission;
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->permissionModel);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function testItCanBeInstantiated()
    {
        $expectations = [
            \Illuminate\Database\Eloquent\Model::class,
            \Kodeine\Acl\Models\Eloquent\Permission::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->permissionModel);
        }
    }

    public function testItHasRelationships()
    {
        $rolesRelationship = $this->permissionModel->roles();

        $this->assertInstanceOf(BelongsToMany::class, $rolesRelationship);

        $this->assertInstanceOf(
            \Kodeine\Acl\Models\Eloquent\Role::class,
            $rolesRelationship->getRelated()
        );
    }

    public function testItCanCreate()
    {
        $attributes = [
            'name'        => 'Create users',
            'slug'        => 'auth.users.create',
            'description' => 'Allow to create users',
        ];

        $permission = $this->permissionModel->create($attributes);

        $this->assertEquals($attributes['name'], $permission->name);
        $this->assertEquals([$attributes['slug'] => true], $permission->slug);
        $this->assertEquals($attributes['description'], $permission->description);

        $this->assertDatabaseHas('permissions', [
            'name'        => 'Create users',
            'description' => 'Allow to create users',
        ]);
    }

    public function testItCanUpdate()
    {
        $attributes = [
            'name'        => 'Create users',
            'slug'        => 'auth.users.create',
            'description' => 'Allow to create users',
        ];

        $permission        = $this->permissionModel->create($attributes);
        $updatedAttributes = [
            'name'        => 'Update users',
            'slug'        => 'auth.users.update',
            'description' => 'Allow to update users',
        ];

        $this->assertDatabaseHas('permissions', [
            'name'        => 'Create users',
            'description' => 'Allow to create users',
        ]);

        $permission->update($updatedAttributes);

        $this->assertDatabaseHas('permissions', [
            'name'        => 'Update users',
            'description' => 'Allow to update users',
        ]);
        $this->assertDatabaseMissing('permissions', $attributes);
    }

    public function testItCanDelete()
    {
        $attributes = [
            'name'        => 'Create users',
            'slug'        => 'auth.users.create',
            'description' => 'Allow to create users',
        ];

        $permission = $this->permissionModel->create($attributes);

        $this->assertDatabaseHas('permissions', [
            'name'        => 'Create users',
            'description' => 'Allow to create users',
        ]);

        $permission->delete();

        $this->assertDatabaseMissing('permissions', $attributes);
    }
}
