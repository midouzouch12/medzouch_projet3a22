<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry; 
use App\Repository\AuthorRepository; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Author; 
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\Request;


final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    #[Route('/addAuth2', name: 'add_author')]
    public function addAuth(ManagerRegistry $em ,Request $request): Response
    {
        $auth1= new Author();
       $form=$this->createForm(AuthorType::class,$auth1);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->getManager()->persist($auth1);
            $em->getManager()->flush();
            return $this->redirectToRoute('get_author_2');
        }
        return $this->render('author/AddAuthor.html.twig', [
            'f'=>$form->createView()
        ]);
    }

    #[Route('/updateAuth2/{id}',name:'app_author_update')]
    public function updateAuthor(Request $req,EntityManagerInterface $em,Author $author
    ,AuthorRepository $AuthorRepository){
        //$author = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
        $em->flush();
        return $this->redirectToRoute('get_author_2');
        }
       
        return $this->render('author/updateAuthor.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    #[Route('/deleteAuth2/{id}', name: 'app_delete')]
    public function deleteAuthor(ManagerRegistry $ManagerRegistry,AuthorRepository $AuthorRepository,$id): Response
    {
        $auth = $AuthorRepository->find($id);
        $ManagerRegistry->getManager()->remove($auth);
        $ManagerRegistry->getManager()->flush();
        return $this->redirectToRoute('get_author_2');}

     #[Route('/homeAuth', name: 'get_author_2')]
         public function getAll2(AuthorRepository $AuthorRepository): Response
         {
        $authors = $AuthorRepository->findAll();

        return $this->render('author/home.html.twig', [
            'authors' => $authors,
        ]);
    }

///////////////statique////////////////////////////////////////////////
     #[Route('/author/showAuthor', name: 'app_showAuthor')]
    public function showAuthor(): Response
    {
        return $this->render('author/show.html.twig ', [
            'name' => 'name',
        ]);
    }
#[Route('/author/listAuthor', name: 'app_listAuthor')]
    public function listAuthor(): Response
    {

         $authors = array(
array('id' => 0, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
array('id' => 1, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 2, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);

        return $this->render('author/list.html.twig ', [
            'authors' => $authors
        ]);
    }
    
    #[Route('/author/{id}', name: 'author_details')]
public function authordetails(int $id): Response
{
    $authors = array(
array('id' => 0, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
array('id' => 1, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 2, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);
    $author = $authors[$id] ;

    return $this->render('author/details.html.twig', [
        'author' => $author
    ]);
}

#[Route('/addAuthorType ', name: 'app_AuthorType_add ', methods: ['GET', 'POST'])]
    public function add(  ManagerRegistry  $entityManager): Response
    {
        $author2 = new Author();
        $author2 ->setUsername('auth3');
        $author2 ->setEmail('auth3@gmail.com');
        $entityManager->getManager()->persist($author2);
        $entityManager->getManager()->flush();

        
       
        return  new Response('Author Added');
    }

    #[Route('/Authorgetall ', name: 'app_Authorgetall ')]
    public function getall(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->findAll();
    
        return $this->render('author/listAuthorType.html.twig', [
            'author' => $author
        ]);
    }

 #[Route('/deleteAuthorType', name: 'app_AuthorType_delete', methods: ['GET', 'POST'])]   
public function delete(AuthorRepository $authorRepository, ManagerRegistry $entityManager): Response
{
    $author = $authorRepository->findOneBy(['Username' => 'auth3']);
    
    if ($author) {
        $entityManager = $entityManager->getManager();
        $entityManager->remove($author);
        $entityManager->flush();
        
    }
    
    return  new Response('Author deleted');
}

#[Route('/updateAuthorType', name: 'app_AuthorType_update', methods: ['GET', 'POST'])]
public function update(AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
{
    $author = $authorRepository->findOneBy(['Username' => 'auth2']);
    
    if ($author) {
        
        $author->setUsername('authupdate'); 
        $author->setEmail('authupdate@gmail.com'); 
        $entityManager->flush();
        
    }
    
    return  new Response('Author updated');
}
    

}
