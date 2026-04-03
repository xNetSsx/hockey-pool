<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\TournamentParticipant;
use App\Entity\User;
use App\Repository\TournamentParticipantRepository;
use App\Repository\UserRepository;
use App\Service\Manager\TournamentParticipantManager;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin')]
class ParticipantAdminController extends AbstractController
{
    #[Route('/participants', name: 'admin_participants')]
    public function participants(
        ActiveTournamentProvider $activeTournamentProvider,
        TournamentParticipantRepository $participantRepo,
        UserRepository $userRepo,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný aktivní turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $participants = $participantRepo->findByTournament($tournament);
        $participantUserIds = array_map(
            static fn (TournamentParticipant $p) => $p->getUser()->getId(), $participants
        );
        $allUsers = $userRepo->findBy([], ['username' => 'ASC']);

        return $this->render('admin/participants.html.twig', [
            'tournament' => $tournament,
            'participants' => $participants,
            'availableUsers' => array_filter(
                $allUsers, static fn (User $u) => !in_array($u->getId(), $participantUserIds, true)
            ),
        ]);
    }

    #[Route('/participants/add', name: 'admin_participant_add', methods: ['POST'])]
    #[IsCsrfTokenValid('participant_add')]
    public function participantAdd(
        Request $request,
        ActiveTournamentProvider $activeTournamentProvider,
        UserRepository $userRepo,
        TournamentParticipantManager $participantManager,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný aktivní turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $userId = $request->getPayload()->getInt('user_id');
        $user = $userRepo->find($userId);

        if (null !== $user && $participantManager->add($user, $tournament)) {
            $this->addFlash('success', sprintf('%s přidán do turnaje.', $user->getUsername()));
        }

        return $this->redirectToRoute('admin_participants');
    }

    #[Route('/participants/{id}/remove', name: 'admin_participant_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsCsrfTokenValid('participant_remove')]
    public function participantRemove(
        TournamentParticipant $participant,
        TournamentParticipantManager $participantManager,
    ): Response {
        $username = $participant->getUser()->getUsername();
        $participantManager->remove($participant);

        $this->addFlash('success', sprintf('%s odebrán z turnaje.', $username));

        return $this->redirectToRoute('admin_participants');
    }

    #[Route('/participants/{id}/toggle-paid', name: 'admin_participant_toggle_paid', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsCsrfTokenValid('participant_paid')]
    public function participantTogglePaid(
        TournamentParticipant $participant,
        TournamentParticipantManager $participantManager,
    ): Response {
        $participantManager->togglePaid($participant);

        return $this->redirectToRoute('admin_participants');
    }
}
