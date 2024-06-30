<?php

namespace App\Controller;
use App\Entity\InvoiceItem;
use App\Form\InvoiceItemType;
use App\Repository\InvoiceItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/invoice-item')]
class InvoiceItemController extends AbstractController
{
    #[Route('/', name:'invoice_item_index',methods:['GET'])]
    public function index(InvoiceItemRepository $invoiceItemRepository): Response 
    {
        return $this->render('invoice-item/index.html.twig',[
            'invoice_items'=>$invoiceItemRepository->findAll(),
        ]);
    }

    #[Route('/new',name:'invoice_item_add', methods: ['GET','POST'])]
    public function add(Request $request , PersistenceManagerRegistry $doctrine): Response 
    {
        $invoiceItem = new InvoiceItem();
        $form = $this->createForm(InvoiceItemType::class,$invoiceItem);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($invoiceItem);
            $entityManager->flush(); 

            return $this->redirectToRoute('invoice_item_index');
        }

        return $this->render('invoice-item/new.html.twig',[
            'invoice_item'=> $invoiceItem,
            'form'=> $form->createView(),
        ]);
    }
    #[Route('/{id}', name:'invoice_item_show',methods:['GET'])]
    public function show(InvoiceItem $invoiceItem):Response 
    {
        return $this->render('invoice_item/show.html.twig',[
            'invoice_item'=> $invoiceItem,
        ]);
    }
    #[Route('/{id}/edit',name:'invoice_item_edit',methods:['GET','POST'])]
    public function edit(Request $request, InvoiceItem $invoiceItem,   PersistenceManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(InvoiceItemType::class, $invoiceItem);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->$doctrine->getManager()->flush();

            return $this->redirectToRoute('invoice_item_index');
        }
        return $this->render('invoice_item/edit.html.twig',[
            'invoice_item'=> $invoiceItem,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name:'invoice_item_delete', methods: ['POST'])]
    public function delete(Request $request, InvoiceItem $invoiceItem): Response
    {
        if($this->isCsrfTokenValid('delete'.$invoiceItem->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManeger();
            $entityManager->remove($invoiceItem);
            $entityManager->flush();
        }
        return $this->redirectToRoute('invoice_item_index');
    }
}