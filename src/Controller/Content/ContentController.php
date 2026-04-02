<?php

declare(strict_types=1);

namespace App\Controller\Content;

use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContentController extends AbstractController
{
    #[Route('/pravidla', name: 'rules_page')]
    public function rules(ActiveTournamentProvider $activeTournamentProvider): Response
    {
        $tournament = $activeTournamentProvider->getActiveTournament();

        return $this->render('content/page.html.twig', [
            'tournament' => $tournament,
            'title' => 'Pravidla',
            'field' => 'rules',
            'content' => $tournament?->getRulesContent(),
            'emptyMessage' => 'Pravidla zatím nebyla nastavena.',
        ]);
    }

    #[Route('/manual', name: 'manual_page')]
    public function manual(ActiveTournamentProvider $activeTournamentProvider): Response
    {
        $tournament = $activeTournamentProvider->getActiveTournament();

        return $this->render('content/page.html.twig', [
            'tournament' => $tournament,
            'title' => 'Manuál',
            'field' => 'manual',
            'content' => $tournament?->getManualContent(),
            'emptyMessage' => 'Manuál zatím nebyl vytvořen.',
        ]);
    }
}
