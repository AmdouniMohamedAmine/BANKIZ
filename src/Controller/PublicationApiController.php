<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Symfony\Entity\Publication;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PublicationApiController extends AbstractController
{
    /**
     * @Route("/publication/ajouterPublication", name="ajouterPublication")
     */
    public function ajouterPublicationMobile(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $Titre_publication = $request->get("titre");
        $Description_publication = $request->get("description");
        $categorie_publication = $request->get("categorie");
        $date_publication = new \DateTime('@' . strtotime('now'));
        $image_publication = $request->get("Image");

        $publication = new Publication();
        $publication->setTitrePublication($Titre_publication);
        $publication->setDescriptionPublication($Description_publication);
        $publication->setCategoriePublication($categorie_publication);
        $publication->setDatePublication($date_publication);
        $publication->setImagePublication($image_publication);

        $em->persist($publication);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($publication);

        return new JsonResponse($formatted);

    }

    /**
     * @Route("/publication/modifierPublication/{id}", name="modifierPublication")
     */
    public function modifierUtilisateurMobile(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
//        $id = $request->get("id");
        $publication = $em->getRepository(Publication::class)->find($id);

        $Titre_publication = $request->get("Titre");
        $Description_publication = $request->get("Description");
        $categorie_publication = $request->get("categorie");
        $image_publication = $request->get("Image");



        $publication->setCinU($Titre_publication);
        $publication->setNomU($Description_publication);
        $publication->setPrenomU($categorie_publication);
        $publication->setEmailU($image_publication);

        $em->persist($publication);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Votre article a été modifié avec succès !");
    }
    /**
     * @Route("/publication/supprimerPublication/{id}", name="supprimerPublication")
     */
    public function supprimerPublication($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pub = $em->getRepository(Publication::class)->find($id);
        $em->remove($pub);
        $em->flush();
        return new JsonResponse("Publication a bien été supprimé !");

    }
    /**
     * @Route("/publication/afficherPublication", name="afficherPublication")
     */
    public function afficherPublication()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur= $em->getRepository(Publication::class)->findAll();

        //RESPONSE JSON FROM OUR SERVER
        $normalizer = new ObjectNormalizer();

        //JOIN ERROR
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            if (method_exists($object, 'getId')) {
                return $object->getId();
            }
        });

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($utilisateur);

        return new JsonResponse($formatted);
    }
}
