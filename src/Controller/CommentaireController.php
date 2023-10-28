<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commentaire;
use App\Form\CommentaireType;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire", name="commentaire")
     */
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
    /**
     * @Route("/afficherC_back", name="afficherC_back")
     */
    public function afficherC_back(): Response
    {
        $form=$this->getDoctrine()->getRepository(Commentaire::class);
        $Commentaire = $form->findAll();
        return $this->render('commentaire/afficherC_back.html.twig',
            [
                'formC'=>$Commentaire
            ]);
    }
    /**
     * @Route("/ajouterC_back", name="ajouterC_back")
     */
    public function ajouterC_back(Request $request)
    {
        $Commentaire = new Commentaire();
        $date = new \DateTime('@' .strtotime('now'));
        $Commentaire->setDateCommentaire($date);

        $form=$this->createForm(CommentaireType::class,$Commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em= $this->getDoctrine()->getManager();
            $em->persist($Commentaire);
            $em->flush();
            return $this->redirectToRoute('afficherC_back');
        }
        return $this->render('commentaire/ajouterC_back.html.twig',
            [
                'formAjouterC'=>$form->createView()
            ]);
    }
    /**
     * @Route("/supprimerC_back/{id}", name="supprimerC_back")
     */
    public function supprimerC_back($id): Response
    {
        $Commentaire=$this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Commentaire);
        $em->flush();
        return $this->redirectToRoute('afficherC_back');
    }
    /**
     * @Route("/modifierC_back/{id}", name="modifierC_back")
     */
    public function modifierC_back(\Symfony\Component\HttpFoundation\Request $request, $id): Response
    {
        $Commentaire = $this->getDoctrine()->getRepository(Commentaire::class) ->find($id);
        $form=$this->createForm(CommentaireType::class, $Commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $Commentaire->setIsPublished(1);
            $em->flush();
            return $this->redirectToRoute('afficherC_back');
        }
        return $this->render('commentaire/ajouterC_back.html.twig', [
            'formAjouterC'=> $form->createView()
        ]);
    }
///////////////////Front///////////////////

}
