<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceController extends AbstractController
{
    #[Route('/mon-espace', name: 'app_espace')]
    public function index(): Response
    {
        $pseudo = $this->getUser()?->getPseudo() ?? 'utilisateur';

        return $this->render('espace/index.html.twig', [
            'pseudo' => $pseudo,
        ]);
    }
}