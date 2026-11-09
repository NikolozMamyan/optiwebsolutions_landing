<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\ProspectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AdminController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authentication): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) return $this->redirectToRoute('admin_dashboard');

        return $this->render('admin/login.html.twig', ['error' => $authentication->getLastAuthenticationError(), 'email' => $authentication->getLastUsername()]);
    }

    #[Route('/admin/logout', name: 'admin_logout', methods: ['POST'])]
    public function logout(): never
    {
        throw new \LogicException('Handled by the security firewall.');
    }

    #[Route('/admin', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(ProspectRepository $prospects, BlogRepository $blogs): Response
    {
        $today = (new \DateTimeImmutable('today', new \DateTimeZone('Europe/Paris')))->setTimezone(new \DateTimeZone('UTC'));

        return $this->render('admin/dashboard.html.twig', ['total' => $prospects->count([]), 'today' => $prospects->countSince($today), 'blogs' => $blogs->count([]), 'prospects' => $prospects->page(1)]);
    }

    #[Route('/admin/prospects', name: 'admin_prospects', methods: ['GET'])]
    public function prospects(Request $request, ProspectRepository $prospects): Response
    {
        $total = $prospects->count([]);
        $pages = max(1, (int) ceil($total / 20));
        $page = max(1, min($pages, $request->query->getInt('page', 1)));

        return $this->render('admin/prospects.html.twig', ['prospects' => $prospects->page($page), 'total' => $total, 'page' => $page, 'pages' => $pages]);
    }
}
