<?php

namespace UsersBundle\Services;

use Doctrine\ORM\EntityManager;
use UsersBundle\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsers()
    {
        return $this->em->getRepository('UsersBundle:Users')->findAll();

    }

    public function getUserWhitOutEmail()
    {
        $repo = $this->em->getRepository('UsersBundle:Users');
        $query = $repo->createQueryBuilder('q')
        ->where("q.email != ''")
        ->getQUery();
        $result = $query->getResult();
        return $result;
    }

    public function insertUsers($newUser)
    {
            
        $user = new Users();
        $user->setUsername($newUser->get('username'));
        $user->setEmail($newUser->get('email'));
        $user->setPassword($newUser->get('encode'));

        $this->em->persist($user);
        $flush = $this->em->flush();
        
        $flush !== null ? $return = ['Status' => 'kO'] : $return = ['Status' => 'Ok'];
        
        return $return;
    }

    public function removeUserById($id)
    {
        $repo = $this->em->getRepository('UsersBundle:Users');
        $userId  = $repo->find($id);

        $this->em->remove($userId);
        $flush =  $this->em->flush();

        $flush !== null ? $return = ['Status' => 'kO'] : $return = ['Status' => 'Ok'];
        
        return $return;
    }
}


