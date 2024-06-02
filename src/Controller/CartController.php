<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    // Affiche le panier de l'utilisateur
    #[Route('/cart', name: 'cart_index')]
    public function index(Request $request): Response
    {
        // Récupère le panier de la session
        $cart = $request->getSession()->get('cart', []);

        // Affiche dans le twig
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    // Ajoute un article au panier
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Article $article, Request $request): Response
    {
        // Met la quantité par défaut à 1
        $quantity = $request->query->get('quantity', 1);

        //Récupère la session
        $session = $request->getSession();
        // Récupère le panier depuis la session
        $cart = $session->get('cart', []);

        // Si l'article est déjà dans le panier rajoute en quantité, sinon, ajoute la quantité demandé
        if (isset($cart[$article->getId()])) {
            $cart[$article->getId()]['quantity'] += $quantity;
        } else {
            $cart[$article->getId()] = [
                'article' => $article,
                'quantity' => $quantity,
            ];
        }

        // Enregistre le panier MAJ dans la session
        $session->set('cart', $cart);

        // Redirige vers la page duy panier
        return $this->redirectToRoute('cart_index');
    }

    // Supprime un article du paneir
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Article $article, Request $request): Response
    {
        // Récupère la session
        $session = $request->getSession();
        // Récupère le panier de la session
        $cart = $session->get('cart', []);

        // Si l'article est dans le panier, le supprime
        if (isset($cart[$article->getId()])) {
            unset($cart[$article->getId()]);
        }

        // Enregistre le panier MAJ dans la session
        $session->set('cart', $cart);

        // Redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    // Affiche la page du paiement
    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(Request $request): Response
    {
        // Récupère l'utilisateur, si non conencté, redirige vers la page de login
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // <- Page de login à modifier
        }

        // Récupère le panier de la session
        $cart = $request->getSession()->get('cart', []);

        // Affiche la page du paiement
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
        ]);
    }
}
