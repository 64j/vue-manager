<?php

namespace VueManager\Traits;

use VueManager\Exceptions\PermissionException;

trait ServicePermissionTrait
{
    /**
     * @param string $permission
     * @return array
     */
    protected function getPermission(string $permission): array
    {
        return $this->permissions[$permission] ?? [];
    }

    /**
     * @return void
     * @throws \VueManager\Exceptions\PermissionException
     */
    protected function hasPermissionsCreate()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('create') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }

    /**
     * @return void
     * @throws \VueManager\Exceptions\PermissionException
     */
    protected function hasPermissionsRead()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('read') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }

    /**
     * @return void
     * @throws \VueManager\Exceptions\PermissionException
     */
    protected function hasPermissionsUpdate()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('update') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }

    /**
     * @return void
     * @throws \VueManager\Exceptions\PermissionException
     */
    protected function hasPermissionsDelete()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('delete') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }

    /**
     * @return void
     * @throws \VueManager\Exceptions\PermissionException
     */
    protected function hasPermissionsList()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('list') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }
}
