<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Symfony\Component\Routing\Annotation\Route;

// class SessionController extends AbstractController
// {
//     #[Route('/session', name: 'app_session')]
//     public function index(SessionInterface $session): Response
//     {
//         // Récupère le panier de la session ou initialise un panier vide
//         $panier = $session->get('panier', []);

//         return $this->render('session/index.html.twig', [
//             'controller_name' => 'SessionController',
//             'panier' => $panier,
//         ]);
//     }

//     #[Route('/add_to_cart/{id}', name: 'add_to_cart')]
//     public function addToCart($id, SessionInterface $session): Response
//     {
//         // Récupère le panier de la session ou initialise un panier vide
//         $panier = $session->get('panier', []);

//         // Ajouter l'article au panier (ici, l'ID de l'article est ajouté)
//         // Vous pouvez ajouter plus de détails sur l'article si nécessaire
//         if (!isset($panier[$id])) {
//             $panier[$id] = 1;  // Si l'article n'est pas dans le panier, l'ajouter avec une quantité de 1
//         } else {
//             $panier[$id]++;  // Si l'article est déjà dans le panier, augmenter la quantité
//         }

//         // Mettre à jour la session avec le nouveau panier
//         $session->set('panier', $panier);

//         // Rediriger vers la page des articles ou où vous voulez après l'ajout au panier
//         return $this->redirectToRoute('app_session');
//     }
// }


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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