<?php


namespace App\Helper;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserHelper extends AbstractController
{
    public function CreateUser($user, $password) {
        $em = $this->getDoctrine()->getManager();
        $userEntity = new User();
        $userEntity->setEmail($user);
        $userEntity->setPassword($password);

        $em->persist($userEntity);
        $em->flush();
    }
}