<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener]
final readonly class LoginListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $isFirstLogin = $user->isFirstLogin();

        $user->setLastLogin(new DateTimeImmutable());
        $this->em->flush();

        if ($isFirstLogin) {
            /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface $session */
            $session = $event->getRequest()->getSession();
            /** @phpstan-ignore method.notFound */
            $session->getFlashBag()->add('info', 'Vítej! Nastav si prosím vlastní heslo.');

            $event->setResponse(new RedirectResponse(
                $this->urlGenerator->generate('change_password'),
            ));
        }
    }
}
