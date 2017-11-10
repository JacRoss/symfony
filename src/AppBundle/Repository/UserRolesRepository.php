<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 13.10.2017
 * Time: 19:01
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Symfony\Component\Validator\Constraints\DateTime;

class UserRolesRepository extends \Doctrine\ORM\EntityRepository
{
    public function add(User $user, Role $role, $dateTime)
    {
        $model = new UserRole();
        $model->setRole($role);
        $model->setUser($user);
        $model->setExpireIn($dateTime);
        $this->_em->persist($model);
        $this->_em->flush();
    }

    public function destroyExpired()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $this->createQueryBuilder('r')
            ->delete()
            ->andWhere('r.expire_in < :date')
            ->setParameter('date', $now)
            ->getQuery()
            ->execute();
    }
}