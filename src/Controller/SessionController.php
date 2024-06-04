<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        dump($cart); // Debugging: voir le contenu de la session
        die(); // Stopper l'exécution pour vérifier la sortie

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
            'cart' => $cart,
        ]);
    }

    #[Route('/add_to_cart/{id}', name: 'add_to_cart')]
    public function addToCart($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        dump($cart); // Debugging: voir le panier avant l'ajout

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $session->set('cart', $cart);
        dump($session->get('cart')); // Debugging: voir le panier après l'ajout
        die(); // Stopper l'exécution pour vérifier la sortie

        return $this->redirectToRoute('app_session');
    }
}
