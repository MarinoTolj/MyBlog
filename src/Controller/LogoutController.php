<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends AbstractController
{

    public function index(Request $request): Response
    {
        $request->getSession()->clear();

        return $this->redirectToRoute("homePage");
    }
}
