<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tournament;
use App\Service\Manager\TournamentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin')]
class ContentAdminController extends AbstractController
{
    #[Route('/tournaments/{id}/content/{field}', name: 'admin_tournament_content', requirements: ['id' => '\d+', 'field' => 'rules|manual'], methods: ['GET'])]
    public function tournamentContent(Tournament $tournament, string $field): Response
    {
        $content = $field === 'rules' ? $tournament->getRulesContent() : $tournament->getManualContent();

        return $this->render('admin/tournament_content_edit.html.twig', [
            'tournament' => $tournament,
            'field' => $field,
            'title' => $field === 'rules' ? 'Pravidla' : 'Manuál',
            'content' => $content ?? '',
        ]);
    }

    #[Route('/tournaments/{id}/content/{field}', name: 'admin_tournament_content_save', requirements: ['id' => '\d+', 'field' => 'rules|manual'], methods: ['POST'])]
    #[IsCsrfTokenValid('tournament_content')]
    public function tournamentContentSave(
        Tournament $tournament,
        string $field,
        Request $request,
        TournamentManager $manager,
    ): Response {
        $content = $request->getPayload()->getString('content');

        if ($field === 'rules') {
            $tournament->setRulesContent($content ?: null);
        } else {
            $tournament->setManualContent($content ?: null);
        }

        $manager->save($tournament);

        $label = $field === 'rules' ? 'Pravidla' : 'Manuál';
        $this->addFlash('success', sprintf('%s pro "%s" uložena.', $label, $tournament->getName()));

        return $this->redirectToRoute('admin_tournament_content', [
            'id' => $tournament->getId(),
            'field' => $field,
        ]);
    }
}
