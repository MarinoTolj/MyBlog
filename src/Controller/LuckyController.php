<?php
 namespace App\Controller;

 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;

 class LuckyController extends AbstractController
 {

     public function number(): Response
     {
         $number = random_int(0, 100);

         return $this->render('lucky/number.html.twig', [
             'number' => $number,
         ]);
     }
 }