<?php

namespace App\Controller;
use App\Entity\QuoteItem;
use App\Form\QuoteItemType;
use App\Repository\QuoteItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


#[Route('/quote_item')]
class QuoteItemController extends AbstractController
{
    #[Route('/', name:'quote_item_index',methods:['GET'])]
    public function index(QuoteItemRepository $quoteItemRepository): Response 
    {
        return $this->render('quote_item/index.html.twig',[
            'quote_items'=>$quoteItemRepository->findAll(),
        ]);
    }

    #[Route('/{productName}/new',name:'quote_item_new', methods: ['GET','POST'])]
    public function add(Request $request, String $productName, PersistenceManagerRegistry $doctrine): Response 
    {
        $quoteItem = new QuoteItem();
        $form = $this->createForm(QuoteItemType::class,$quoteItem);
        $form->product = $productName;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($quoteItem);
            $entityManager->flush(); 

            return $this->redirectToRoute('quote_item_index');
        }

        return $this->render('quote_item/new.html.twig',[
            'quote_item'=> $quoteItem,
            'form'=> $form->createView(),
        ]);
    }
    #[Route('/{id}', name:'quote_item_show',methods:['GET'])]
    public function show(QuoteItem $quoteItem):Response 
    {
        return $this->render('quote_item/show.html.twig',[
            'quote_item'=> $quoteItem,
        ]);
    }
    #[Route('/{id}/edit',name:'quote_item_edit',methods:['GET','POST'])]
    public function edit(Request $request, QuoteItem $quoteItem): Response
    {
        $form = $this->createForm(QuoteItemType::class, $quoteItem);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quote_item_index');
        }
        return $this->render('quote_item/edit.html.twig',[
            'quote_item'=> $quoteItem,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name:'quote_item_delete', methods: ['POST'])]
    public function delete(Request $request, QuoteItem $quoteItem): Response
    {
        if($this->isCsrfTokenValid('delete'.$quoteItem->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManeger();
            $entityManager->remove($quoteItem);
            $entityManager->flush();
        }
        return $this->redirectToRoute('quote_item_index');
    }
}