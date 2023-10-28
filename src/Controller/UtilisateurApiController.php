<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class UtilisateurApiController extends AbstractController
{
    /**
     * @Route("/utilisateur/ajouterU", name="ajouterUtilisateurMobile")
     */
    public function ajouterUtilisateurMobile(Request $request ,UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        $cin_u = $request->get("cin");
        $nom_u = $request->get("nom");
        $prenom_u = $request->get("prenom");
        $date_naissance = new \DateTime('@' . strtotime('now'));
        $email_u = $request->get("email");
        $num_tel = $request->get("tel");
        $role = $request->get("role");
        $mot_de_passe = $request->get("mdp");

        if(!filter_var($email_u, FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid");
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setCinU($cin_u);
        $utilisateur->setNomU($nom_u);
        $utilisateur->setPrenomU($prenom_u);
        $utilisateur->setDateNaissance($date_naissance);
        $utilisateur->setEmailU($email_u);
        $utilisateur->setNumTel($num_tel);
        $utilisateur->setRole($role);
        $utilisateur->setMotDePasse($passwordEncoder->encodePassword(
            $utilisateur,
            $mot_de_passe
        ));

        $em->persist($utilisateur);
        $em->flush();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($utilisateur);

        return new JsonResponse($formatted);


    }

    /**
     * @Route("/utilisateur/modifierU/{id}", name="modifierUtilisateurMobile")
     */
    public function modifierUtilisateurMobile(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
//        $id = $request->get("id");
        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);

        $cin_u = $request->get("cin");
        $nom_u = $request->get("nom");
        $prenom_u = $request->get("prenom");
        //$date_naissance = new \DateTime('@' . strtotime('now'));
        $email_u = $request->get("email");
        $num_tel = $request->get("tel");
        $role = $request->get("role");
        $mot_de_passe = $request->get("mdp");



        $utilisateur->setCinU($cin_u);
        $utilisateur->setNomU($nom_u);
        $utilisateur->setPrenomU($prenom_u);
        //$utilisateur->setDateNaissance($date_naissance);
        $utilisateur->setEmailU($email_u);
        $utilisateur->setNumTel($num_tel);
        $utilisateur->setRole($role);
        $utilisateur->setMotDePasse($mot_de_passe);

        $em->persist($utilisateur);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Votre profile a été modifié avec succès !");
    }

    /**
     * @Route("/utilisateur/supprimerU/{id}", name="supprimerUtilisateurMobile")
     */
    public function supprimerUtilisateurMobile($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Utilisateur::class)->find($id);
        $em->remove($prod);
        $em->flush();
        return new JsonResponse("Le utilisateur a bien été supprimé !");

    }

    /**
     * @Route("/utilisateur/afficherU", name="afficherUtilisateurMobile")
     */
    public function afficherUtilisateurMobile()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur= $em->getRepository(Utilisateur::class)->findAll();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        //JOIN ERROR
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            if (method_exists($object, 'getId')) {
                return $object->getId();
            }
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($utilisateur);

        return new JsonResponse($formatted);
    }
}
