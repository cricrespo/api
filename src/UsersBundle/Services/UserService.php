<?php

namespace UsersBundle\Services;

use Doctrine\ORM\EntityManager;
use UsersBundle\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

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
        $user->getId() !== null ? $return = ['Status' => 'OK'] : $return = ['Status' => 'KO'];
        
        return $return;
    }

    public function updateUser($updateUser)
    {
        $userId  = $this->getUserId($updateUser->get('id'));
        
        $updateUser->get('username') ? $userId->setUsername($updateUser->get('username')) : '';
        $updateUser->get('email') ? $userId->setEmail($updateUser->get('email')) : '';
        $updateUser->get('encode') ? $userId->setPassword($updateUser->get('encode')) : '';
        
        $this->em->persist($userId);
        $flush = $this->em->flush();
        
        $flush !== null ? $return = ['Status' => 'kO'] : $return = ['Status' => 'Ok'];
        
        return $return;
    }
    

    public function removeUserById($id)
    {
        
        $userId  = $this->getUserId($id);

        $this->em->remove($userId);
        $flush =  $this->em->flush();

        $flush !== null ? $return = ['Status' => 'kO'] : $return = ['Status' => 'Ok'];
        return $return;
    }

    public function getUserId($id)
    {
        $repo = $this->em->getRepository('UsersBundle:Users');
        $userId  = $repo->find($id);
        return $userId;
    }

    
}


