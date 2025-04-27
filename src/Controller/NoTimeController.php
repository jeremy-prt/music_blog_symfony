<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NoTimeController extends AbstractController
{
    #[Route('/no/time', name: 'app_no_time')]
    public function index(): Response
    {
        return $this->render('no_time/index.html.twig', [
            'controller_name' => 'NoTimeController',
        ]);
    }
}
