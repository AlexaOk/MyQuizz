<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Categorie;
use App\Entity\User;
use App\Form\HistoryType;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/history")
 */
class HistoryController extends Controller
{
    /**
     * @Route("/{id}/user", name="history_index", methods="GET")
     */
    public function index(HistoryRepository $historyRepository, Request $request, $id,  SessionInterface $session): Response
    {
        $CategorieRepository = $this->getDoctrine()->getRepository(Categorie::class);
        $categoriename=$CategorieRepository->findOneBy(['id' => $id]);
        $categoriename=$categoriename->getName();

        return $this->render('history/index.html.twig', ['histories' => $historyRepository->findAll(), 'categorie'=>$id, 'categorieName' => $categoriename]);
    }

    /**
     * @Route("/new", name="history_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $history = new History();
        $form = $this->createForm(HistoryType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
            $em->flush();

            return $this->redirectToRoute('history_index');
        }

        return $this->render('history/new.html.twig', [
            'history' => $history,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="history_show", methods="GET")
     */
    public function show(History $history): Response
    {
        return $this->render('history/show.html.twig', ['history' => $history]);
    }

    /**
     * @Route("/{id}/edit", name="history_edit", methods="GET|POST")
     */
    public function edit(Request $request, History $history): Response
    {
        $form = $this->createForm(HistoryType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('history_edit', ['id' => $history->getId()]);
        }

        return $this->render('history/edit.html.twig', [
            'history' => $history,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="history_delete", methods="DELETE")
     */
    public function delete(Request $request, History $history): Response
    {
        if ($this->isCsrfTokenValid('delete'.$history->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($history);
            $em->flush();
        }

        return $this->redirectToRoute('history_index');
    }
}
