<?php


namespace App\Helper;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserHelper extends AbstractController
{
    public function CreateUser($email, $password, UserPasswordHasherInterface $hasher) {
        $em = $this->getDoctrine()->getManager();

        $user = $userEntity = new User();
        $hashedPassword = $hasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);


        $em->persist($userEntity);
        $em->flush();
    }
}