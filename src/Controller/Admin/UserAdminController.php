<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use App\Service\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class UserAdminController extends AbstractController
{
    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $repo): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $repo->findBy([], ['username' => 'ASC']),
        ]);
    }

    #[Route('/users/new', name: 'admin_user_new')]
    public function userNew(Request $request, UserManager $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var bool $admin */
            $admin = $form->get('admin')->getData();
            $user->setRoles($admin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']);
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $manager->hashPassword($user, $plainPassword);
            $manager->save($user);

            $this->addFlash('success', 'Uživatel vytvořen.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nový uživatel',
            'back' => 'admin_users',
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit', requirements: ['id' => '\d+'])]
    public function userEdit(User $user, Request $request, UserManager $manager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->get('admin')->setData($user->hasRole('ROLE_ADMIN'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var bool $admin */
            $admin = $form->get('admin')->getData();
            $user->setRoles($admin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']);

            /** @var string|null $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $manager->hashPassword($user, $plainPassword);
            }

            $manager->save($user);
            $this->addFlash('success', 'Uživatel upraven.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Upravit: ' . $user->getUsername(),
            'back' => 'admin_users',
        ]);
    }
}
