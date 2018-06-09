<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
  /**
  * @Route("/register", name="user_registration")
  */
  public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
  {
    $ip=$request->getClientIp();

    // 1) build the form
    $user = new User();
    $form = $this->createForm(UserType::class, $user);

    // 2) handle the submit (will only happen on POST)
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      // 3) Encode the password (you could also do this via Doctrine listener)
      $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);
      $user->setIp($ip);

      // 4) save the User!
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

      $username=$user->getUsername();
      $email=$user->getEmail();
      // ... do any other work - like sending them an email, etc
      // maybe set a "flash" success message for the user
      $message = (new \Swift_Message('Hello Email'))
      ->setFrom('alexaokito@gmail.com')
      ->setTo($email)
      ->setBody(
        $this->renderView(
                // templates/emails/registration.html.twig
                'emails/registration.html.twig', array("username"=>$username)
            ),
            'text/html')
      ;

      $mailer->send($message);


      return $this->redirectToRoute('home');
    }

    return $this->render(
      'registration/register.html.twig',
      array('form' => $form->createView())
    );
  }

  /**
  * @Route("/register/activate/{username}", name="user_activation")
  */
  public function activate(Request $request, $username)
  {
      $ip=$request->getClientIp();

      $entityManager = $this->getDoctrine()->getManager();
      $UserRepository = $this->getDoctrine()->getRepository(User::class);
      $user=$UserRepository->findOneBy(['ip' => $ip, 'username'=>$username]);

      $user->setIsActive(1);
      $entityManager->flush();

    return $this->render("registration/valid.html.twig", array('ip'=>$ip, 'username'=>$username));
  }
}
