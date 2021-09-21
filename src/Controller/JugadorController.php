<?php

namespace App\Controller;

use App\Entity\Jugador;
use App\Entity\Posicio;
use App\Form\JugadorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class JugadorController extends AbstractController
{
    /**
     * @Route("/jugador", name="jugador")
     */
    public function index()
    {
        return $this->render('jugador/index.html.twig', [
            'controller_name' => 'JugadorController',
        ]);
    }

    /**
     * @Route("/jugador/list", name="jugador_list")
     */
    function list() {
        $jugadors = $this->getDoctrine()
            ->getRepository(Jugador::class)
            ->findAll();

        $posicions = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->findAll();

        //codi de prova per visualitzar l'array de jugadors
        /*dump($jugadors);
        exit();*/

        return $this->render('jugador/list.html.twig', ['jugadors' => $jugadors, 'posicions' => $posicions]);
    }

    /**
     * @Route("/jugador/edit/{id<\d+>}", name="jugador_edit")
     */
    public function edit($id, Request $request, SluggerInterface $slugger)
    {
        $jugador = $this->getDoctrine()
            ->getRepository(Jugador::class)
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe JugadorType
        $form = $this->createForm(JugadorType::class, $jugador, array('submit' => 'Desar'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imatge = $form->get('imatge')->getData();

            if ($imatge) {
                $nImatge = pathinfo($imatge->getClientOriginalName(), PATHINFO_FILENAME);

                $safeImatge = $slugger->slug($nImatge);

                $nomImatge = $nImatge . '-' . uniqid() . '.' . $imatge->guessExtension();

                // try {
                $imatge->move(
                    $this->getParameter('imatges_directory'),
                    $nomImatge
                );
                // } catch (FileException $e) {
                //throw $th;
                // }

                $jugador->setImatge($nomImatge);
            }

            $jugador = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jugador);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Jugador ' . $jugador->getNom() . ' desat!'
            );

            return $this->redirectToRoute('jugador_list');
        }

        return $this->render('jugador/jugador.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar jugador',
        ));
    }

    /**
     * @Route("/jugador/delete/{id}", name="jugador_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $jugador = $this->getDoctrine()
            ->getRepository(Jugador::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $nomJugador = $jugador->getNom();
        $entityManager->remove($jugador);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Jugador ' . $nomJugador . ' eliminat!'
        );

        return $this->redirectToRoute('jugador_list');
    }

    /**
     * @Route("/jugador/new", name="jugador_new")
     */
    function new (Request $request, SluggerInterface $slugger) {
        $jugador = new Jugador();

        //sense la classe JugadorType faríem:
        /*$form = $this->createFormBuilder($jugador)
        ->add('tasca', EntityType::class, array('class' => Tasca::class,
        'choice_label' => 'nom'))
        ->add('nom', TextType::class)
        ->add('duracio', IntegerType::class)
        ->add('save', SubmitType::class, array('label' => 'Crear Jugador'))
        ->getForm();*/

        //podem personalitzar el text passant una opció 'submit' al builder de la classe JugadorType
        $form = $this->createForm(JugadorType::class, $jugador, array('submit' => 'Crear Jugador'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $jugador = $form->getData();

            $imatge = $form->get('imatge')->getData();

            if ($imatge) {
                $nImatge = pathinfo($imatge->getClientOriginalName(), PATHINFO_FILENAME);

                $safeImatge = $slugger->slug($nImatge);

                $nomImatge = $nImatge . '-' . uniqid() . '.' . $imatge->guessExtension();
            }

            try {
                $imatge->move(
                    $this->getParameter('imatges_directory'),
                    $nomImatge
                );
            } catch (FileException $e) {
                //throw $th;
            }

            $jugador->setImatge($nomImatge);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jugador);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou jugador ' . $jugador->getNom() . ' creat!'
            );

            return $this->redirectToRoute('jugador_list');
        }

        return $this->render('jugador/jugador.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou Jugador',
        ));
    }

    /**
     * @Route("/jugador/search", name="jugador_search")
     */
    public function search(Request $request)
    {
        //recollim el paràmetre 'term' enviat per post
        $term = $request->request->get('term');

        $jugadors = $this->getDoctrine()
            ->getRepository(Jugador::class)
            ->findLikeNom($term);

        return $this->render('jugador/list.html.twig', [
            'jugadors' => $jugadors,
            'posicions' => $posicions,
            'searchTerm' => $term,
        ]);
    }

    /**
     * @Route("/jugador/filter", name="jugador_filter")
     */
    public function filter(Request $request)
    {
        //recollim el paràmetre 'cat' enviat per post
        $pos = $request->request->get('pos');

        if ($pos != "") {

            $posicio = $this->getDoctrine()
                ->getRepository(Posicio::class)
                ->findBy(array('nom' => $pos));

            $posicions = $this->getDoctrine()
                ->getRepository(Posicio::class)
                ->findAll();

            $jugadors = $this->getDoctrine()
                ->getRepository(Jugador::class)
                ->findBy(array('Posicio' => $posicio));

            return $this->render('jugador/list.html.twig', [
                'jugadors' => $jugadors,
                'posicions' => $posicions,
                'searchTerm' => $pos,
            ]);

        } else {

            // $this->addFlash('warning', 'x');
            return $this->redirectToRoute('jugador_list');

        }
    }
}
