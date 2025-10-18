<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry; 
use App\Repository\BookRepository; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book; 
use App\Entity\Author; 
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addbook', name: 'add_book')]
    public function addbook(ManagerRegistry $em ,Request $request): Response
    {
        $Book1= new Book();
       $form=$this->createForm(BookType::class,$Book1);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->getManager()->persist($Book1);
            $em->getManager()->flush();
            return $this->redirectToRoute('app_books_list');
        }
        return $this->render('book/Addbook.html.twig', [
            'f'=>$form->createView()
        ]);
    }

    #[Route('/updatebook/{id}',name:'app_book_update')]
    public function updateBook(Request $req,EntityManagerInterface $em,Book $Book
    ,BookRepository $BookRepository){
        //$author = $repo->find($id);
        $form = $this->createForm(BookType::class,$Book);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
        $em->flush();
        return $this->redirectToRoute('app_books_list');
        }
       
        return $this->render('book/updatebook.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    #[Route('/deletebook/{id}', name: 'app_book_delete')]
    public function deleteBook(ManagerRegistry $ManagerRegistry,BookRepository $BookRepository,$id): Response
    {
        $Book = $BookRepository->find($id);
        $ManagerRegistry->getManager()->remove($Book);
        $ManagerRegistry->getManager()->flush();
        return $this->redirectToRoute('app_books_list');}

    #[Route('/homebooks', name: 'app_books_list')]
    public function listBooks(BookRepository $bookRepository): Response
    {
        
        $publishedBooks = $bookRepository->findPublishedBooks();
              
        $publishedCount = $bookRepository->countPublishedBooks();
        $unpublishedCount = $bookRepository->countUnpublishedBooks();
        
        return $this->render('book/homebook.html.twig', [
            'publishedBooks' => $publishedBooks,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
        ]);
    }

}
