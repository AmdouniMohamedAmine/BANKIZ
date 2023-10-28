<?php

namespace App\Controller;

use App\Entity\Cheques;
use App\Form\ChequeFormType;
use App\Repository\ChequesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChequeController extends AbstractController
{
    /**
     * @Route("/cheque", name="cheque")
     */
    public function index(): Response
    {
        return $this->render('Cheque/index.html.twig', [
            'controller_name' => 'ChequeController',
        ]);
    }
    /**
     * @Route("/ajouterE", name="addE")
     */
    public function ajouterE(Request $request): Response
    {
        $cheques=new Cheques();
        $form=$this->createForm(ChequeFormType::class,$cheques);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($cheques);
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render('Cheque/AjouterCheque.html.twig',
            [
                'form'=>$form->createView()
            ]);
    }
    /**
     * @Route("/afficheE", name="afficheE")
     */
    public function afficheE()
    {
        $repo = $this->getDoctrine()->getRepository(Cheques::class);
        $cheques=$repo->findAll();
        return $this->render('Cheque/AfficherCheque.html.twig',
            ['c'=>$cheques]);
    }
    /**
     * @Route("/ModifierE/{id}", name="ModifierE")
     */
    public function ModifierE(Request $req, $id)
    {

            $em=$this->getDoctrine()->getManager();
            $prod = $em->getRepository(Cheques::class)->find($id);
            $form = $this->createForm(ChequeFormType::class,$prod);
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render('Cheque/ModifierCheque.html.twig',array("form"=>$form->createView()));
    }
    /**
    * @Route ("/triupcheque", name="croissantcheque")
    */
    public function orderSujetASC(ChequesRepository $repository){
        $plans=$repository->triSujetASC();
        return $this->render('Cheque/AfficherCheque.html.twig', [
            'c' => $plans,
        ]);
    }

    /**
     * @Route("/tridowncheque", name="decroissantcheque")
     */
    public function orderSujetDESC(ChequesRepository $repository){
        $plans=$repository->triSujetDESC();
        return $this->render('Cheque/AfficherCheque.html.twig', [
            'c' => $plans,

        ]);
    }


}
