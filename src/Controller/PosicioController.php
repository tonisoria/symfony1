<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\PosicioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Posicio;

class PosicioController extends AbstractController
{
    /**
     * @Route("/posicio", name="posicio")
     */
    public function index()
    {
        return $this->render('posicio/index.html.twig', [
            'controller_name' => 'PosicioController',
        ]);
    }

    /**
     * @Route("/posicio/list", name="posicio_list")
     */
    public function list()
    {
        $posicions = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->findAll();

        //codi de prova per visualitzar l'array de posicions
         /*dump($posicions);
         exit();*/

        return $this->render('posicio/list.html.twig', ['posicions' => $posicions]);
    }

    /**
     * @Route("/posicio/edit/{id<\d+>}", name="posicio_edit")
     */
    public function edit($id, Request $request)
    {
        $posicio = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->find($id);

        //fent això el text del boto submit tindria el valor per defecte 'Enviar'
        //$form = $this->createForm(PosicioType::class, $posicio);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe PosicioType 
        // http://www.keganv.com/passing-arguments-controller-file-type-symfony-3/
        $form = $this->createForm(PosicioType::class, $posicio, array('submit'=>'Desar'));
        
        //també ho podríem fer d'una altra manera: sobreescriure el botó save 
        /*$form = $this->createForm(PosicioType::class, $posicio);
        $form->add('save', SubmitType::class, array('label' => 'Desar'));*/

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $posicio = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($posicio);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Posicio '.$posicio->getNom().' desada!'
            );

            return $this->redirectToRoute('posicio_list');
        }

        return $this->render('posicio/posicio.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar Posicio',
        ));
    }

    /**
     * @Route("/posicio/delete/{id<\d+>}", name="posicio_delete")
     */
    public function delete($id, Request $request)
    {
        $posicio = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $nomPosicio = $posicio->getNom();
        $entityManager->remove($posicio);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Posicio '.$nomPosicio.' eliminada!'
        );

        return $this->redirectToRoute('posicio_list');
    }

    /**
     * @Route("/posicio/new", name="posicio_new")
     */
    public function new(Request $request)
    {
        $posicio = new Posicio();

        //sense la classe PosicioType faríem:
        /*$form = $this->createFormBuilder($posicio)
            ->add('nom', TextType::class)
            ->add('prioritat', IntegerType::class)
            ->add('completada', CheckboxType::class)
            ->add('save', SubmitType::class, array('label' => 'Crear Posicio'))
            ->getForm();*/

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe PosicioType 
        $form = $this->createForm(PosicioType::class, $posicio, array('submit'=>'Crear Posicio'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $posicio = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($posicio);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nova posicio '.$posicio->getNom().' creada!'
            );

            return $this->redirectToRoute('posicio_list');
        }

        return $this->render('posicio/posicio.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nova Posicio',
        ));
    }

    /**
     * @Route("/posicio/search", name="posicio_search")
     */
    public function search(Request $request)
    {
        //recollim el paràmetre 'term' enviat per post
        $term = $request->request->get('term');

        $posicions = $this->getDoctrine()
            ->getRepository(Posicio::class)
            ->findLikeNom($term);

        return $this->render('posicio/list.html.twig', [
            'posicions' => $posicions,
            'searchTerm' => $term,
        ]);
    }
}