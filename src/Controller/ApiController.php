<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use App\Entity\Jugador;
use App\Entity\Posicio;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ApiController extends AbstractFOSRestController
{

    /**
     * GET Route annotation.
     * @Get("/api/jugadors", name="get_jugadors")
     */
    public function getProductes()
    {
        $repository = $this->getDoctrine()->getRepository(Jugador::class);
        // look for *all* Product objects
        $jugadors = $repository->findAll();

        // el camp imatge que hem de subministrar al client es la URL que serveix la imatge
        foreach ($jugadors as $jugador) {
            $jugador->setImatge('/api/jugadorImatge/' . $jugador->getId());
        }

        $data = $jugadors;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/api/posicions", name="get_posicions")
     */
     public function getPosicions()
     {
         $repository = $this->getDoctrine()->getRepository(Posicio::class);
         // look for *all* Product objects
         $posicions = $repository->findAll();
 
         $data = $posicions;
 
         // construim la resposta HTTP i l'enviem
         $view = $this->view($data, 200);
         $view->setFormat('json');
         $view->setHeader('Access-Control-Allow-Origin','*');
         return $this->handleView($view);
     }

    /**
     * GET Route annotation.
     * @Get("/api/jugadorImatge/{id}")
     */
    public function getProducteImatge($id)
    {
        $repository = $this->getDoctrine()->getRepository(Jugador::class);
        $jugador = $repository->find($id);
        $file = $this->getParameter('imatges_directory') . '/' . $jugador->getImatge();
        //http://symfony.com/doc/current/components/http_foundation.html#serving-files
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', 'image/jpeg');

        return $response;
    }

    /**
     * GET Route annotation.
     * @Get("/api/jugador/{id}")
     */
    public function getProducte($id)
    {

        $repository = $this->getDoctrine()->getRepository(Jugador::class);

        $jugador = $repository->find($id);

        // el camp imatge que hem de subministrar al client es la URL que serveix la imatge

        $jugador->setImatge('/api/jugadorImatge/' . $jugador->getId());

        $data = $jugador;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * POST Route annotation.
     * @Post("/api/jugador")
     */
    public function insertJugador(Request $request, SluggerInterface $slugger)
    {
        $jugador = new Jugador();
        $nom = $request->request->get('nom');
        $sobrenom = $request->request->get('sobrenom');
        $equip = $request->request->get('equip');
        $posicioId = $request->request->get('_posicio');
        $posicio = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->find($posicioId);
        $imatge = $request->files->get('imatge');
        $imatgeFile = $imatge;

        if ($imatge) {
            $nImatge = pathinfo($imatge->getClientOriginalName(), PATHINFO_FILENAME);

            $safeImatge = $slugger->slug($nImatge);

            $nomImatge = $nImatge . '-' . uniqid() . '.' . $imatge->guessExtension();

            $imatge->move($this->getParameter('imatges_directory'), $nomImatge);

            $jugador->setImatge($nomImatge);

        }

        $jugador->setNom($nom);
        $jugador->setSobrenom($sobrenom);
        $jugador->setEquip($equip);
        $jugador->setPosicio($posicio);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($jugador);
        $entityManager->flush();

        $data = $jugador;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        return $this->handleView($view);

    }

}
