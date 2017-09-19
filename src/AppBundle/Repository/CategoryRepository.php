<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 19.09.17
 * Time: 17:55
 */

namespace AppBundle\Repository;


class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll()
    {
        $categories = [];

        $result = $this->findAll();

        foreach ($result as $value) {
            $categories[$value->getName()] = $value->getId();
        }

        return $categories;
    }

}