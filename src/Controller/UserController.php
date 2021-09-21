<?php

namespace App\Controller;

use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Firebase\JWT\JWT;

class UserController extends AbstractFOSRestController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * POST Route annotation.
     * @Post("/firebase/credencials")
     */
    public function getToken(Request $request, UserRepository $em)
    {
        $user = $request->request->get('username');
        $password = $request->request->get("password");
        $find = $em->findOneBy(array('username' => $user));
        // $find = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('username' => $user));

        if ($find != null) {
            $data = "";
            $hash = $find->getPassword();
            if (password_verify($password, $hash)) {
                $payload = [
                    "sub" => $user . $password,
                    "iat" => time(),
                    "exp" => time() + 60,
                    "jti" => "idusr-" . $find->getId(),
                ];
                $jwt = JWT::encode($payload, $this->getParameter('key'));
                
                $find->setApiToken($jwt);
                $em = $this->getDoctrine()->getManager();
                $em->persist($find);
                $em->flush();

                $data = $jwt;
            } else {
                $data = "La contrasenya no és correcta";
            }
            $view = $this->view($data, 200);
        } else {
            $data = "L'Usuari no s'ha trobat";
            $view = $this->view($data, 400);
        }

        $view->setFormat('json');
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $this->handleView($view);
    }
}
