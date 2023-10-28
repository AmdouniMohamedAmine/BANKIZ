<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\OperationCredit;
use App\Form\CreditType;
use App\Form\OperationCreditType;
use App\Repository\CreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends AbstractController
{
    /**
     * @Route("/credit", name="credit")
     */
    public function index(): Response
    {
        return $this->render('credit/index.html.twig', [
            'controller_name' => 'CreditController',
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/ajoutCredit",name="ajoutCredit")
     */
    function AjoutCredit(Request $request){
        $credit=new Credit();
        $form=$this->createForm(CreditType::class,$credit);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($credit);
            $em->flush();
            return $this->redirectToRoute('affichCredit');
        }
        return $this->render("credit/ajoutCredit.html.twig",["f"=>$form->createView()]);
    }

    /**
     * @param CreditRepository $repository
     * @param $id
     * @param $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/modifCredit/{id}",name="modifCredit")
     */
    function ModifCredit(CreditRepository $repository,$id,Request $request){
        $credit=$repository->find($id);
        $form=$this->createForm(CreditType::class,$credit);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichCredit');
        }
        return $this->render("credit/modifCredit.html.twig",["f"=>$form->createView()]);
    }


    /**
     * @param CreditRepository $repository
     * @return Response
     * @Route ("/credit/affichCredit",name="affichCredit")
     */
    function AfficheCredit(CreditRepository $repository){
        $credits=$repository->findAll();
        return $this->render("credit/affichCredit.html.twig",["credits"=>$credits]);
    }

    /**
     * @param CreditRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/credit/suppCredit/{id}",name="suppCredit")
     */
    function SuppCredit(CreditRepository $repository,$id){
        $credit=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($credit);
        $em->flush();
        return $this->redirectToRoute('affichCredit');
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/ajoutCreditFront",name="ajoutCreditFront")
     */
    function AjoutCreditFront(Request $request){
        $Credit=new Credit();
        $form=$this->createForm(CreditType::class,$Credit);
        $form->remove('tauxInteret');
        $form->remove('decision');
        $form->remove('etatCredit');
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($Credit);
            $em->flush();
            return $this->redirectToRoute('ajoutCreditFront');
        }
        return $this->render("Credit/ajoutCreditFront.html.twig",["for"=>$form->createView()]);
    }
      /**
     * @Route("/credit/rechercheBytype", name="rechercheBytype")
     */
    public function rechercheBytype(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $Credit = $em->getRepository(Credit::class)->findAll();
        if($request->isMethod("POST"))
        {
            $typeCredit =$request->get('typeCredit');
            $Credit =$em->getRepository(Credit::class)->findBy(array('typeCredit'=>$typeCredit));
        }
        return $this->render("Credit/Recherche.html.twig",array('Credit'=>$Credit));


    }
}
