<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 10.11.2017
 * Time: 12:02
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserAddRoleForm
{
    private $roleId;
    private $date;

    public function setRoleId(int $roleId)
    {
        $this->roleId = $roleId;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function getDate()
    {
        return $this->date;
    }
}