<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Service\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'change_password')]
    public function __invoke(
        Request $request,
        UserManager $userManager,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = $form->get('newPassword')->getData();
            $userManager->hashPassword($user, $newPassword);
            $userManager->save($user);

            $this->addFlash('success', 'Heslo bylo změněno.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
