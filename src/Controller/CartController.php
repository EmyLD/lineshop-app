<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupère le panier de la session
        $cart = $request->getSession()->get('cart', []);
        $total = 0;

        // Transforme les IDs d'articles en objets Article
        $cartDetails = [];
        foreach ($cart as $articleId => $quantity) {
            $article = $entityManager->getRepository(Article::class)->find($articleId);
            if ($article) {
                $cartDetails[] = [
                    'article' => $article,
                    'quantity' => $quantity,
                ];
                // Calcule le total du panier
                $total += $article->getPrice() * $quantity;
            }
        }

        // Affiche dans le twig
        return $this->render('cart/index.html.twig', [
            'cart' => $cartDetails,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Article $article, Request $request): Response
    {
        // Met la quantité par défaut à 1
        $quantity = 1;

        // Récupère la session
        $session = $request->getSession();
        // Récupère le panier depuis la session
        $cart = $session->get('cart', []);

        // Si l'article est déjà dans le panier, rajoute en quantité, sinon, ajoute la quantité demandée
        if (isset($cart[$article->getId()])) {
            $cart[$article->getId()] += $quantity;
        } else {
            $cart[$article->getId()] = $quantity;
        }

        // Enregistre le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Redirige vers la page d'origine
        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Article $article, Request $request): Response
    {
        // Récupère la session
        $session = $request->getSession();
        // Récupère le panier de la session
        $cart = $session->get('cart', []);

        // Si l'article est dans le panier, décrémente la quantité
        if (isset($cart[$article->getId()])) {
            if ($cart[$article->getId()] > 1) {
                $cart[$article->getId()]--;
            } else {
                // Si la quantité est 1, retire l'article du panier
                unset($cart[$article->getId()]);
            }
        }

        // Enregistre le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'utilisateur, si non connecté, redirige vers la page de login
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // <- Page de login à modifier
        }

        // Récupère le panier de la session
        $cart = $request->getSession()->get('cart', []);

        // Transforme les IDs d'articles en objets Article
        $cartDetails = [];
        foreach ($cart as $articleId => $quantity) {
            $article = $entityManager->getRepository(Article::class)->find($articleId);
            if ($article) {
                $cartDetails[] = [
                    'article' => $article,
                    'quantity' => $quantity,
                ];
            }
        }

        // Affiche la page du paiement
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cartDetails,
        ]);
    }
}
