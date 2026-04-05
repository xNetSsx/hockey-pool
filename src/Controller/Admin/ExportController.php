<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tournament;
use App\Service\Export\TournamentExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class ExportController extends AbstractController
{
    #[Route('/export/tournament/{id}', name: 'admin_export_tournament', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function tournament(
        Tournament $tournament,
        TournamentExportService $exportService,
    ): BinaryFileResponse {
        $tmpFile = $exportService->writeToTempFile($tournament);

        $filename = sprintf(
            'hockey-pool-%s-%s.xlsx',
            $tournament->getSlug(),
            (new \DateTimeImmutable())->format('Y-m-d'),
        );

        $response = new BinaryFileResponse($tmpFile);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
