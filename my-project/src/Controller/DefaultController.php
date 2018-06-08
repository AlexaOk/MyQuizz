<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
  /**
  * @Route("/", name="home", methods="GET")
  */
  public function show(Request $request)
  {
    $categorie = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->findAll();

    return $this->render('base.html.twig', array(
      'categorie' => $categorie,
    ));
  }


  /**
  * @Route("/logout")
  */
  public function logout()
  {
    return $this->render('base.html.twig',array(
      'categorie' => $categorie,
    ));
  }
}
