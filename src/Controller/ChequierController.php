<?php

namespace App\Controller;

use App\Entity\Chequier;
use App\Form\ChequierFormType;
use App\Repository\ChequierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChequierController extends AbstractController
{
    /**
     * @Route("/chequier", name="chequier")
     */
    public function index(): Response
    {
        return $this->render('chequier/index.html.twig', [
            'controller_name' => 'ChequierController',
        ]);
    }
    /**
     * @Route("/AjouterChequier", name="AjouterChequier")
     */
    public function ajouter(Request $request): Response
    {
        $cheques=new Chequier();
        $form=$this->createForm(ChequierFormType::class,$cheques);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($cheques);
            $em->flush();
            return $this->redirectToRoute('cheque');
        }
        return $this->render('chequier/AjouterChequier.html.twig',
            [
                'form'=>$form->createView()
            ]);
    }
    /**
     * @Route("/supprimer/{id}", name="suppE")
     */
    public function supprimer($id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $chequier = $em->getRepository(Chequier::class)->find($id);
        $em->remove($chequier);
        $em->flush();
        return $this->redirectToRoute('afficherchequierb');
    }
    /**
     * @Route("/afficherchequierb", name="afficherchequierb")
     */
    public function afficheE()
    {
        $repo = $this->getDoctrine()->getRepository(Chequier::class);
        $chequier=$repo->findAll();
        return $this->render('chequier/index.html.twig',
            ['chequier'=>$chequier]);
    }
    /**
     * @Route ("/triup", name="croissant")
     */
    public function orderSujetASC(ChequierRepository $repository){
        $plans=$repository->triSujetASC();
        return $this->render('chequier/index.html.twig', [
            'chequier' => $plans,
        ]);
    }

    /**
     * @Route("/tridown", name="decroissant")
     */
    public function orderSujetDESC(ChequierRepository $repository){
        $plans=$repository->triSujetDESC();
        return $this->render('chequier/index.html.twig', [
            'chequier' => $plans,

        ]);
    }
}
