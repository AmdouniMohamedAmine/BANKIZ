<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use App\Form\PublicationType;
use App\Form\CommentaireType;
use App\Form\RechercherCategorieType;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use App\Repository\PublicationRepository;
use Egulias\EmailValidator\Parser\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="publication")
     */
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }
//////////////// Back ////////////////
    /**
     * @Route("/afficherP_back", name="afficherP_back")
     */
    public function afficherP_back(): Response
    {
        $form=$this->getDoctrine()->getRepository(Publication::class);
        $Publication = $form->findAll();
        return $this->render('publication/afficherP_back.html.twig',
        [
            'formP'=>$Publication,
        ]);
    }

    /**
     * @Route("/ajouterP_back", name="ajouterP_back")
     */
    public function ajouterP_back(Request $request)
    {
        $Publication = new Publication();
        $date = new \DateTime('@' .strtotime('now'));
        $Publication->setDatePublication($date);
        $form=$this->createForm(PublicationType::class,$Publication);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file=$Publication->getImagePublication();
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                //... handle exception if something happens during file upload
            }
            $em= $this->getDoctrine()->getManager();
            $Publication->setImagePublication($filename);
            $em->persist($Publication);
            $em->flush();
            return $this->redirectToRoute('afficherP_back');
        }
        return $this->render('publication/ajouterP_back.html.twig',
        [
            'formAjouterP'=>$form->createView()
        ]);
    }

    /**
     * @Route("/supprimerP_back/{id}", name="supprimerP_back")
     */
    public function supprimerP_back($id): Response
    {
        $Publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Publication);
        $em->flush();
        return $this->redirectToRoute('afficherP_back');
    }
    /**
     * @Route("/triPublication", name="triPublication")
     */
    public function triPublication()
    {
        $Publication= $this->getDoctrine()->getRepository(Publication::class)->TriParDatePublication();
        return $this->render("publication/afficherP_back.html.twig",array('formP'=>$Publication));
    }
    /**
     * @Route("/triNomClient", name="triNomClient")
     */
    public function triNomClient()
    {
        $publication= $this->getDoctrine()->getRepository(Publication::class)->TriParNomClient();
        return $this->render("publication/afficherP_back.html.twig",array('formP'=>$publication));
    }
    /**
     * @Route("/triCategorie", name="triCategorie")
     */
    public function triCategorie()
    {
        $publication= $this->getDoctrine()->getRepository(Publication::class)->TriParCategorie();
        return $this->render("publication/afficherP_back.html.twig",array('formP'=>$publication));
    }
    /**
     * @Route("/rechercheCategorie_back", name="rechercheCategorie_back")
     */
    public function rechercheCategorie_back(Request $request, PublicationRepository $repository)
    {
        // Trouver tous les articles
        $publications= $repository->findAll();

        //recherche
        $searchForm = $this->createForm(RechercherCategorieType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $categorie_publication = $searchForm['categorie_publication']->getData();
            $resultOfSearch = $repository->searchPublication($categorie_publication);
            return $this->render('publication/rechercheCategorie_back.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchPublication" => $searchForm->createView()));
        }
        return $this->render('publication/afficherCategorie_back.html.twig', array(
            "publications" => $publications,
            "searchPublication" => $searchForm->createView()));
    }
    /**
     * @Route ("/statistiques", name="statistiques")
     */
    public function statistiques(PublicationRepository $publicationRepository)
    {
        //on va chercher les categories
        $categories = $publicationRepository->findAll();
        $categNom = [];

        foreach ($categories as $publication) {
            $categNom[] = $publication->getCategoriePublication();
            $categColor[] = $publication->getCategoriePublication();
        }

        return $this->render('publication/statistiques.html.twig', [
            'categNom' => json_encode($categNom)
        ]);
    }
    //////////////// Front ////////////////
    /**
     * @Route("/afficherP_front", name="afficherP_front")
     */
    public function afficherP_front(): Response
    {
        $form=$this->getDoctrine()->getRepository(Publication::class);
        $Publication = $form->findAll();
        return $this->render('publication/afficherP_front.html.twig',
            [
                'formPF'=>$Publication
            ]);
    }
    /**
     * @Route("/afficherPD_front/{id}", name="afficherPD_front")
     */
    public function afficherPD_front($id,Publication $pub, Request $request, CommentaireRepository $commentaireRepository ): Response
    {
        $commentaires = $commentaireRepository->findByExampleField($pub);

        $commentaire = new Commentaire();
        $date = new \DateTime('@' .strtotime('now'));
        $commentaire->setDateCommentaire($date);
        $commentaire->setIdPublication($pub);
        $user = $this->getUser();
        $commentaire->setNomClient($user);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $commentaire = $form->getData();
            // on recupere le contenu dy champ parentid
            //$parentid = $commentaires->getParent();
            // on va chercher le commentaire correspondant
            $em= $this->getDoctrine()->getManager();
            //$parent = $em->getRepository(Commentaire::class)->find($parentid);
            // on definit le parent
            //$commentaire->setParent($parent);

            $commentaire->setIsPublished(0);
            $em->persist($commentaire,$pub, null);
            $em->flush();
            $this->addFlash('success', 'Commentaire créé avec succès Attendez la confirmation de lAdministrateur ');
            return $this->redirectToRoute('afficherPD_front', ['id'
            => $pub->getId()]);
        }
        return $this->render('publication/afficherPD_front.html.twig', [
            'formPD' =>$pub,
            'form' => $form->createView(),
            'commentaires' => $commentaires
        ]);
    }
    /**
     * @Route("/ajouterP_front", name="ajouterP_front")
     */
    public function ajouterP_front(Request $request)
    {
        if (! $this->getUser()) {
            $this->addFlash('error', 'Vous devez dabord vous connecter ! ');
            return $this->redirectToRoute('login');
        }
        $Publication = new Publication();
        $date = new \DateTime('@' .strtotime('now'));
        $Publication->setDatePublication($date);
        $user = $this->getUser();
        $Publication->setNomClient($user);
        $form=$this->createForm(PublicationType::class,$Publication);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file=$Publication->getImagePublication();
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                //... handle exception if something happens during file upload
            }
            $em= $this->getDoctrine()->getManager();
            $Publication->setImagePublication($filename);

            $em->persist($Publication);
            $em->flush();
            $this->addFlash('success', 'Poste créé avec succès ');
            return $this->redirectToRoute('afficherP_front');
        }
        return $this->render('publication/ajouterP_front.html.twig',
            [
                'formAjouterPF'=>$form->createView()
            ]);
    }
    /**
     * @Route("/supprimerP_front/{id}", name="supprimerP_front")
     */
    public function supprimerP_front($id, Publication $pub): Response
    {
        if (! $this->getUser()) {
            $this->addFlash('error', 'Vous devez dabord vous connecter ! ');
            return $this->redirectToRoute('login');
        }
        if($pub->getNomClient() != $this->getUser()) {
            $this->addFlash('info','Vous pouvez supprimer que vos articles !');
            return $this->redirectToRoute('afficherP_front');
        }
        $Publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Publication);
        $em->flush();
        $this->addFlash('info', 'Poste supriméé avec succès ');
        return $this->redirectToRoute('afficherP_front');
    }
    /**
     * @Route("/modifierP_front/{id}", name="modifierP_front")
     */
    public function modifierP_front(\Symfony\Component\HttpFoundation\Request $request, $id, Publication $pub): Response
    {
        if (! $this->getUser()) {
            $this->addFlash('error', 'Vous devez dabord vous connecter ! ');
            return $this->redirectToRoute('login');
        }
        if($pub->getNomClient() != $this->getUser()) {
            $this->addFlash('info','Vous pouvez modifier que vos articles');
            return $this->redirectToRoute('afficherP_front');
        }
        $Publication = $this->getDoctrine()->getRepository(Publication::class) ->find($id);
        $form=$this->createForm(PublicationType::class, $Publication);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $file=$Publication->getImagePublication();
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                //... handle exception if something happens during file upload
            }
            $Publication->setImagePublication($filename);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Poste modifiéé avec succès ');
            return $this->redirectToRoute('afficherP_front');
        }
        return $this->render('publication/ajouterP_front.html.twig', [
            'formAjouterPF'=> $form->createView()
        ]);
    }
    /**
     * @Route("/rechercheCategorie_front", name="rechercheCategorie_front")
     */
    public function rechercheCategorie_front(Request $request, PublicationRepository $repository)
    {
        // Trouver tous les articles
        $publications= $repository->findAll();

        //recherche
        $searchForm = $this->createForm(RechercherCategorieType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $categorie_publication = $searchForm['categorie_publication']->getData();
            $resultOfSearch = $repository->searchPublication($categorie_publication);
            return $this->render('publication/rechercheCategorie_front.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchPublication" => $searchForm->createView()));
        }
        return $this->render('publication/afficherCategorie_front.html.twig', array(
            "publications" => $publications,
            "searchPublication" => $searchForm->createView()));
    }

    /**
     * Permet de liker ou disliker un article
     *
     * @Route ("/like/{id}", name="post_like")
     *
     * @param Publication $publication
     * @param PostLikeRepository $likeRepository
     * @return Response
     */
    public function like (Publication $publication, PostLikeRepository $likeRepository) :Response
    {
        $utilisateur =$this->getUser();
        if(!$utilisateur)
            return $this->redirectToRoute('login');

        if($publication->isLikedByUser($utilisateur))
        {
            $like = $likeRepository->findOneBy([
                'Publication' => $publication,
                'nom_client' => $utilisateur
            ]);
            $em= $this->getDoctrine()->getManager();
            $em->remove($like);
            $em->flush();

           return $this->redirectToRoute('afficherP_front');

        }
        $like = new PostLike();
        $like->setPublication($publication)
            ->setNomClient($utilisateur);

        $em= $this->getDoctrine()->getManager();
        $em->persist($like);
        $em->flush();

        return $this->redirectToRoute('afficherP_front');

    }
}
