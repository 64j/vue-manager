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
    protected function hasCreate()
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
    protected function hasRead()
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
    protected function hasUpdate()
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
    protected function hasDelete()
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
    protected function hasList()
    {
        $app = evolutionCMS();

        foreach ($this->getPermission('list') as $permission) {
            if (!$app->hasPermission($permission)) {
                throw new PermissionException();
            }
        }
    }
}
