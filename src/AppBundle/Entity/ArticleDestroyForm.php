<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 19.09.17
 * Time: 17:43
 */

namespace AppBundle\Entity;


class ArticleDestroyForm
{
    private $category;
    private $date;

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getDate()
    {
        return $this->date;
    }

}