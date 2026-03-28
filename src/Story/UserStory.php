<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public const string POOL = 'users';

    public const array USERNAMES = [
        'Ondra', 'Táda', 'Martin', 'Pavel', 'Váca',
        'Kuba', 'Mééča', 'Honza S', 'Mates', 'Fanda',
    ];

    private const array ADMINS = ['Ondra', 'Táda', 'Honza S'];

    public function build(): void
    {
        foreach (self::USERNAMES as $username) {
            $isAdmin = in_array($username, self::ADMINS, true);

            $this->addState($username, UserFactory::createOne([
                'username' => $username,
                'password' => 'heslo123',
                'roles' => $isAdmin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER'],
            ]), self::POOL);
        }
    }
}
