<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\StatsService;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        $stats = $statsService->getStats();
        $best_ads = $statsService->getAdsStats('DESC');
        $worst_ads = $statsService->getAdsStats('ASC');


        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'best_ads' => $best_ads,
            'worst_ads' => $worst_ads
        ]);
    }
}
