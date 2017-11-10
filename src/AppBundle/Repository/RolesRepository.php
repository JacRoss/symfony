<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 13.10.2017
 * Time: 19:02
 */

namespace AppBundle\Repository;


class RolesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll(): array
    {
        $roles = [];

        $result = $this->findAll();

        foreach ($result as $value) {
            $roles[$value->getName()] = $value->getId();
        }

        return $roles;
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }
}