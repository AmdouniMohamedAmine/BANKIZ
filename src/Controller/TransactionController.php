<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Transaction;
use App\Form\CompteType;
use App\Form\TransactionFrontQRType;
use App\Form\TransactionFrontType;
use App\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

    /**
     * @Route("/Transactions", name="transactions")
     */
    public function AfficherTransactions(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Transaction::class);
        $transactions = $repository->findAll();
        return $this->render('transaction/BackOffice/affichage_transaction.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * @Route("/supprimerTransaction/{id}", name="supprimerTransaction")
     */
    public function SupprimerTransaction($id): Response
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($transaction);
        $entityManager->flush();
        return $this->redirectToRoute('transactions');

    }

    /**
     * @Route("/ajouterTransaction", name="ajouter_transaction")
     */
    public function ajouterTransaction(Request $request): Response
    {
        $transaction = new Transaction();
        $date = new \DateTime('@'.strtotime('now'));
        $transaction->setDateTransaction($date);
        $transaction->setEtatTransaction(0);
        $form = $this->createForm(TransactionType::class,$transaction);
        $form->add("Ajouter Transaction",SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('transactions');
        }
        return $this->render('transaction/BackOffice/ajouterTransaction.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifierTransaction/{id}", name="modifier_transaction")
     */
    public function modifierTransaction(Request $request, $id): Response
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->add("Modifier Transaction",SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('transactions');
        }
        return $this->render('transaction/BackOffice/modifierTransaction.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/TriTransaction", name="tri_etat_trans")
     */
    public function TriEtatTransaction()
    {
        $transactions= $this->getDoctrine()->getRepository(Transaction::class)->TriEtatTransaction();
        return $this->render("transaction/BackOffice/affichage_transaction.html.twig",array('transactions'=>$transactions));
    }

    /**
     * @Route("/TriNomEmetteur", name="tri_nom_emetteur")
     */
    public function TriNomEmetteur()
    {
        $transactions= $this->getDoctrine()->getRepository(Transaction::class)->TriNomEmetteur();
        return $this->render("transaction/BackOffice/affichage_transaction.html.twig",array('transactions'=>$transactions));
    }

    /**
     * @Route("/TriNomRecepteur", name="tri_nom_recepteur")
     */
    public function TriNomRecepteur()
    {
        $transactions= $this->getDoctrine()->getRepository(Transaction::class)->TriNomRecepteur();
        return $this->render("transaction/BackOffice/affichage_transaction.html.twig",array('transactions'=>$transactions));
    }

    /**
     * @Route("/TriDateTransaction", name="tri_date_trans")
     */
    public function TriDateTransaction()
    {
        $transactions= $this->getDoctrine()->getRepository(Transaction::class)->TriDate();
        return $this->render("transaction/BackOffice/affichage_transaction.html.twig",array('transactions'=>$transactions));
    }

    /**
     * @Route("/ValiderTransactionBack/{id}", name="valider_trans_back")
     */
    public function ValiderTransactionBack($id)
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $transaction->setEtatTransaction(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('transactions');
    }

    /**
     * @Route("/AnnulerTransactionBack/{id}", name="annuler_trans_back")
     */
    public function AnnulerTransactionBack($id)
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $transaction->setEtatTransaction(2);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('transactions');
    }

//    ****************** PARTIE FRONT *******************
    /**
     * @Route("/EffectuerTransaction/{id}", name="effectuer_transaction")
     */
    public function effectuerTransaction(Request $request, $id): Response
    {
        $transaction = new Transaction();
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $repository->findOneByFullname($id);
        $date = new \DateTime('@'.strtotime('now'));
        $transaction->setDateTransaction($date);
        $transaction->setEtatTransaction(0);
        $transaction->setFullnameEmetteur($compte);
        $transaction->setRIBEmetteur($compte);
        $form = $this->createForm(TransactionFrontType::class,$transaction);
        $form->add("Ajouter Transaction",SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('mon_compte',  ['id' => $transaction->getRIBEmetteur()->getFullnameClient()->getId()]);
        }
        return $this->render('transaction/FrontOffice/ajouterTransaction.html.twig', [
            'transaction' => $transaction,
            'compte' =>$compte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/EffectuerTransactionqr/{id}/{rib}", name="effectuer_transactionqr")
     */
    public function effectuerTransactionqr(Request $request, $id, $rib): Response
    {
        $transaction = new Transaction();
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $repository->findOneByFullname($id);
        $compte2 = $repository->findOneByRIB($rib);
        $date = new \DateTime('@'.strtotime('now'));
        $transaction->setDateTransaction($date);
        $transaction->setEtatTransaction(0);
        $transaction->setFullnameEmetteur($compte);
        $transaction->setRIBEmetteur($compte);
        $transaction->setRIBRecepteur($compte2->getRIBCompte());
        $transaction->setFullnameRecepteur($compte2->getFullnameClient()->getNomU().$compte2->getFullnameClient()->getPrenomU());
        $form = $this->createForm(TransactionFrontQRType::class,$transaction);
        $form->add("Ajouter Transaction",SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('mon_compte',  ['id' => $transaction->getRIBEmetteur()->getFullnameClient()->getId()]);
        }
        return $this->render('transaction/FrontOffice/ajouterTransactionqr.html.twig', [
            'transaction' => $transaction,
            'compte' =>$compte,
            'recepteur' => $compte2,
            'form' => $form->createView(),
        ]);
    }
}