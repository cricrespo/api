<?php

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UsersBundle\Entity\Users;


class DefaultController extends Controller
{

    const ATTR_PARAM_JSON = 'json';
    const ATTR_PARAM_TEST = 'test';

    public function indexAction()
    {   
        return $this->getUsers();
    }

    public function listAction()
    {   
       return $this->getUsers();
    }

    public function listEamilNotNUllAction()
    {
        return $this->getUserWhitOutEmail();
    }

    public function insertUserAction(Request $request)
    {
        $userService = $this->get('pruebas.user');
        $user = new Users();
        $password = $this->get('security.password_encoder') 
        ->encodePassword($user, $request->request->get('password'));
        $request->request->set('encode', $password);

        $result = $userService->insertUsers($request->request);

        $helpersService = $this->get("app.helpers");
        $httpResponse = $helpersService->collectionToHttpJsonResponse($result);
        return $httpResponse;
    }

    public function removeUserByIdAction($id)
    {
        $userService = $this->get('pruebas.user');
        $result = $userService->removeUserById($id);

        $helpersService = $this->get("app.helpers");
        $httpResponse = $helpersService->collectionToHttpJsonResponse($result);
        return $httpResponse;

    }


    private function getUsers()
    {
        $userService = $this->get("pruebas.user");
        $users = $userService->getUsers();


        $helpersService = $this->get("app.helpers");

        $httpResponse = $helpersService->collectionToHttpJsonResponse($users);

        return $httpResponse;
    }

    private function getUserWhitOutEmail()
    {

        $userService = $this->get("pruebas.user");
        $users = $userService->getUserWhitOutEmail();

        $helpersService = $this->get("app.helpers");

        $httpResponse = $helpersService->collectionToHttpJsonResponse($users);

        return $httpResponse;

    } 
}
