<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Http\Authenticator\Passport;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

/**
 * The default implementation for passports.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class Passport implements UserPassportInterface
{
    use PassportTrait;

    protected $user;

    private $attributes = [];

    /**
     * @param CredentialsInterface $credentials the credentials to check for this authentication, use
     *                                          SelfValidatingPassport if no credentials should be checked
     * @param BadgeInterface[]     $badges
     */
    public function __construct(UserBadge $userBadge, CredentialsInterface $credentials, array $badges = [])
    {
        $this->addBadge($userBadge);
        $this->addBadge($credentials);
        foreach ($badges as $badge) {
            $this->addBadge($badge);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        if (null === $this->user) {
            if (!$this->hasBadge(UserBadge::class)) {
                throw new \LogicException('Cannot get the Security user, no username or UserBadge configured for this passport.');
            }

            $this->user = $this->getBadge(UserBadge::class)->getUser();
        }

        return $this->user;
    }

    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }
}
