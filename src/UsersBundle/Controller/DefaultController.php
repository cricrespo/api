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

    /**
     * @ApiDoc(
     *  description="Return all users",
     *
     *  output={"collection"=false, "collectionName"="classes", "class"="usersBundle\Entity\Users"}
     * )
     */
    
    public function listAction()
    {   
       return $this->getUsers();
    }

    
    /**
     * @ApiDoc(
     *  description="Return all users without email blank",
     *
     *  output={"collection"=false, "collectionName"="classes", "class"="usersBundle\Entity\Users"}
     * )
     */

    public function listEamilNotNullAction()
    {
        return $this->getUserWhitOutEmail();
    }

    /**
     * @ApiDoc(
     *  description="Insert new user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id del curso"
     *      }
     *  },
     *
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="user name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="email"}
     *      {"name"="password", "dataType"="string", "required"=true, "description"="password"}
     *  }
     * )
    */

    public function insertUserAction(Request $request)
    {
        $userService = $this->get('users.user');
        $user = new Users();
        $password = $this->get('security.password_encoder') 
        ->encodePassword($user, $request->request->get('password'));
        $request->request->set('encode', $password);

        $result = $userService->insertUsers($request->request);

        $helpersService = $this->get("app.helpers");
        $httpResponse = $helpersService->collectionToHttpJsonResponse($result);
        return $httpResponse;
    }

    /**
     * @ApiDoc(
     *  description="Update user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id del curso"
     *      }
     *  },
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="user name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="email"}
     *      {"name"="password", "dataType"="string", "required"=true, "description"="password"}
     *  }
     * )
     */

    public function updateUserAction(Request $request)
    {
        $userService = $this->get('users.user');
        if($request->request->get('password')){
            $user = new Users();
            $password = $this->get('security.password_encoder') 
            ->encodePassword($user, $request->request->get('password'));
            $request->request->set('encode', $password);
        }
        
        $result = $userService->updateUser($request->request);

        $helpersService = $this->get("app.helpers");
        $httpResponse = $helpersService->collectionToHttpJsonResponse($result);
        return $httpResponse;
    }

    /**
     * @ApiDoc(
     *  description="Remove user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id del curso"
     *      }
     *  }
     * )
     */

    public function removeUserByIdAction($id)
    {
        $userService = $this->get('users.user');
        $result = $userService->removeUserById($id);

        $helpersService = $this->get("app.helpers");
        $httpResponse = $helpersService->collectionToHttpJsonResponse($result);
        return $httpResponse;

    }

    /**
     * @ApiDoc(
     *  description="Ger user by by id",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id del curso"
     *      }
     *  },
     *
     *  output={"collection"=false, "collectionName"="classes", "class"="UsersBundle\Entity\Users"}
     * )
     */
    public function getUserIdAction($id)
    {
        $userService = $this->get("users.user");
        $user = $userService->getUserId($id);

        $helpersService = $this->get("app.helpers");

        $httpResponse = $helpersService->collectionToHttpJsonResponse($user);

        return $httpResponse;
    }


    private function getUsers()
    {
        $userService = $this->get("users.user");
        $users = $userService->getUsers();

        $helpersService = $this->get("app.helpers");

        $httpResponse = $helpersService->collectionToHttpJsonResponse($users);

        return $httpResponse;
    }
    

    private function getUserWhitOutEmail()
    {

        $userService = $this->get("users.user");
        $users = $userService->getUserWhitOutEmail();

        $helpersService = $this->get("app.helpers");

        $httpResponse = $helpersService->collectionToHttpJsonResponse($users);

        return $httpResponse;

    } 
}
