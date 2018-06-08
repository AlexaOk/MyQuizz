<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\History;
use App\Entity\User;
use App\Form\CategorieType;
use App\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/categorie")
 */
class CategorieController extends Controller
{
    /**
     * @Route("/", name="categorie_index", methods="GET")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findAll();

        return $this->render('categorie/index.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/new", name="categorie_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods="GET")
     */
    public function show(Categorie $categorie, Request $request, SessionInterface $session): Response
    {
      $em = $this->getDoctrine()->getManager();
      $id=$categorie->getId();
      $dql = "SELECT question FROM App\Entity\Question question WHERE question.Category = $id";
      //$dql = "SELECT t0.id AS id_1, t0.question AS question_2, t0.id AS category_id_3 FROM App\Entity\Question t0 WHERE t0.id = $id ";
      $query = $em->createQuery($dql);

      $paginator  = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
      $query, /* query NOT result */
      $request->query->getInt('page', 1)/*page number*/,
      1/*limit per page*/
    );
      return $this->render('categorie/show.html.twig', ['categorie' => $categorie, 'pagination' => $pagination]);
    }

    /**
     * @Route("/{id}/reponse",name="category_response", methods="GET|POST")
     */
     public function reponse(Request $request, Categorie $categorie, $id)
     {
        $user=$this->getUser()->getId();
        $reponse=$request->request->get('reponse');
        $question=$request->request->get('question');
        $categorie=$request->request->get('categorie');

        $UserRepository = $this->getDoctrine()->getRepository(User::class);
        $userid=$UserRepository->findOneBy(['id' => $user]);

        $ReponseRepository = $this->getDoctrine()->getRepository(Reponse::class);
        $reponseid=$ReponseRepository->findOneBy(['id' => $reponse]);

        $QuestionRepository = $this->getDoctrine()->getRepository(Question::class);
        $questionid=$QuestionRepository->findOneBy(['id' => $question]);

        $entityManager = $this->getDoctrine()->getManager();

        $history = new History();
        $history->setQuestionId($questionid);
        $history->setReponseId($reponseid);
        $history->setUserId($userid);
        $history->setCreatedAt(new \DateTime());

        $entityManager->persist($history);

        $entityManager->flush();

        // $categorie = new Categorie();
        // $form = $this->createForm(CategorieType::class, $categorie);
        // $form->handleRequest($request);
        //
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($categorie);
        //     $em->flush();

         return new JsonResponse(['rep'=> $reponse, 'quest'=>$question, 'cat'=>$categorie, 'user'=>$user]);
     }
    /**
     * @Route("/{id}/edit", name="categorie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_edit', ['id' => $categorie->getId()]);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods="DELETE")
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
    * @Route("/{id}/question")
    */
    public function getQuestions(Categorie $categorie)
    {
      dump($categorie->getQuestions()->getValues());
      die();
    }
}
