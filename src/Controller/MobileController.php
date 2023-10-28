<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class MobileController extends AbstractController
{
//    *************************** COMPTE *********************************
    /**
     * @Route("/compte/ajouter", name="ajouterCompteMobile")
     */
    public function ajouterCompteMobile(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $num_compte = $request->get("num_compte");
        $RIB_Compte = $request->get("rib_compte");
        $solde_compte = $request->get("solde_compte");
        $date_creation = new \DateTime('@' . strtotime('now'));
        $type_compte = $request->get("type_compte");
        $seuil_compte = $request->get("seuil_compte");
        $taux_interet = $request->get("taux_interet");
        $fullname_client_id = $request->get("fullname_client_id");
        $client = $this->getDoctrine()->getRepository(Utilisateur::class)->find($fullname_client_id);

        $etat_compte = $request->get("etat_compte");

        $compte = new Compte();
        $compte->setEtatCompte($etat_compte);
        $compte->setRIBCompte($RIB_Compte);
        $compte->setNumCompte($num_compte);
        $compte->setDateCreation($date_creation);
        $compte->setFullnameClient($client);
        $compte->setSeuilCompte($seuil_compte);
        $compte->setSoldeCompte($solde_compte);
        $compte->setTypeCompte($type_compte);
        $compte->setTauxInteret($taux_interet);

        $em->persist($compte);
        $em->flush();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($compte);

        return new JsonResponse($formatted);
    }

    /**
     * @Route("/compte/modifier/{id}", name="modifierCompteMobile")
     */
    public function modifierCompteMobile(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
//        $id = $request->get("id");

        $num_compte = $request->get("num_compte");
        $RIB_Compte = $request->get("rib_compte");
        $solde_compte = $request->get("solde_compte");
        $date_creation = new \DateTime('@' . strtotime('now'));
        $type_compte = $request->get("type_compte");
        $seuil_compte = $request->get("seuil_compte");
        $taux_interet = $request->get("taux_interet");
        $fullname_client_id = $request->get("fullname_client_id");
        $client = $this->getDoctrine()->getRepository(Utilisateur::class)->find($fullname_client_id);
        $etat_compte = $request->get("etat_compte");


//        $compte = new Compte();
        $compte = $em->getRepository(Compte::class)->find($id);
        $compte->setEtatCompte($etat_compte);
        $compte->setRIBCompte($RIB_Compte);
        $compte->setNumCompte($num_compte);
//        $compte->setDateCreation($date_creation);
        $compte->setFullnameClient($client);
        $compte->setSeuilCompte($seuil_compte);
        $compte->setSoldeCompte($solde_compte);
        $compte->setTypeCompte($type_compte);
        $compte->setTauxInteret($taux_interet);

        $em->persist($compte);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Votre compte a été modifié avec succès !");
    }

    /**
     * @Route("/compte/supprimer/{id}", name="supprimerCompteMobile")
     */
    public function supprimerCompteMobile($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Compte::class)->find($id);
        $em->remove($prod);
        $em->flush();
        return new JsonResponse("Le compte a bien été supprimé !");

    }

    /**
     * @Route("/compte/afficher", name="afficherCompteMobile")
     */
    public function afficherCompteMobile()
    {
        $em = $this->getDoctrine()->getManager();
        $compte = $em->getRepository(Compte::class)->findAll();

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
        $formatted = $serializer->normalize($compte);

        return new JsonResponse($formatted);
    }

//    ******************** TRANSACTIONS *******************

    /**
     * @Route("/transaction/ajouter", name="ajouterTransactionMobile")
     */
    public function ajouterTransactionMobile(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $RIB_emetteur = $request->get("rib_emetteur_id");
        $RIB_recepteur = $request->get("rib_recepteur");
        $montant_transaction = $request->get("montant");
        $description_transaction = $request->get("description");
        $date_transaction = new \DateTime('@' . strtotime('now'));
        $fullname_emetteur_id = $request->get("fullname_emetteur_id");
        $fullname_recepteur = $request->get("fullname_recepteur");
        $type_transaction = $request->get("type_transaction");
        $client = $this->getDoctrine()->getRepository(Compte::class)->find($RIB_emetteur);
        $clientEm = $this->getDoctrine()->getRepository(Compte::class)->find($fullname_emetteur_id);

        $etat_transaction = $request->get("etat_transaction");

        $trans = new Transaction();
        $trans->setRIBEmetteur($client);
        $trans->setRIBRecepteur($RIB_recepteur);
        $trans->setMontantTransaction($montant_transaction);
        $trans->setDescriptionTransaction($description_transaction);
        $trans->setDateTransaction($date_transaction);
        $trans->setFullnameRecepteur($fullname_recepteur);
        $trans->setFullnameEmetteur($clientEm);
        $trans->setTypeTransaction($type_transaction);
        $trans->setEtatTransaction($etat_transaction);

        $em->persist($trans);
        $em->flush();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($trans);

        return new JsonResponse($formatted);
    }

    /**
     * @Route("/transaction/modifier/{id}", name="modifierTransactionMobile")
     */
    public function modifierTransactionMobile(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
//        $id = $request->get("id");

        $RIB_emetteur = $request->get("rib_emetteur_id");
        $RIB_recepteur = $request->get("rib_recepteur");
        $montant_transaction = $request->get("montant");
        $description_transaction = $request->get("description");
//        $date_transaction = new \DateTime('@' . strtotime('now'));
        $fullname_emetteur_id = $request->get("fullname_emetteur_id");
        $fullname_recepteur = $request->get("fullname_recepteur");
        $type_transaction = $request->get("type_transaction");
        $client = $this->getDoctrine()->getRepository(Compte::class)->find($RIB_emetteur);
        $clientEm = $this->getDoctrine()->getRepository(Compte::class)->find($fullname_emetteur_id);

        $etat_transaction = $request->get("etat_transaction");

        $trans = $em->getRepository(Transaction::class)->find($id);


        $trans->setRIBEmetteur($client);
        $trans->setRIBRecepteur($RIB_recepteur);
        $trans->setMontantTransaction($montant_transaction);
        $trans->setDescriptionTransaction($description_transaction);
//        $trans->setDateTransaction($date_transaction);
        $trans->setFullnameRecepteur($fullname_recepteur);
        $trans->setFullnameEmetteur($clientEm);
        $trans->setTypeTransaction($type_transaction);
        $trans->setEtatTransaction($etat_transaction);

        $em->persist($trans);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Votre transaction a été modifiée avec succès !");
    }

    /**
     * @Route("/transaction/supprimer/{id}", name="supprimerTransactionMobile")
     */
    public function supprimerTransactionMobile($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Transaction::class)->find($id);
        $em->remove($prod);
        $em->flush();
        return new JsonResponse("La transaction a bien été supprimée !");

    }

    /**
     * @Route("/transaction/afficher", name="afficherTransactionMobile")
     */
    public function afficherTransactionMobile()
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $em->getRepository(Transaction::class)->findAll();

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
        $formatted = $serializer->normalize($trans);

        return new JsonResponse($formatted);
    }
}