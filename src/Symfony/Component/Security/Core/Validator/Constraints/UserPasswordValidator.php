<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Validator\Constraints;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserPasswordValidator extends ConstraintValidator
{
    private $tokenStorage;
    private $hasherFactory;

    public function __construct(TokenStorageInterface $tokenStorage, PasswordHasherFactoryInterface $hasherFactory)
    {
        $this->tokenStorage = $tokenStorage;
        $this->hasherFactory = $hasherFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $password, Constraint $constraint)
    {
        if (!$constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, UserPassword::class);
        }

        if (null === $password || '' === $password) {
            $this->context->addViolation($constraint->message);

            return;
        }

        if (!\is_string($password)) {
            throw new UnexpectedTypeException($password, 'string');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof UserInterface) {
            throw new ConstraintDefinitionException('The User object must implement the UserInterface interface.');
        }

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            trigger_deprecation('symfony/security-core', '5.3', 'Using the "%s" validation constraint without implementing the "%s" interface is deprecated, the "%s" class should implement it.', UserPassword::class, PasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        $salt = $user->getSalt();
        if ($salt && !$user instanceof LegacyPasswordAuthenticatedUserInterface) {
            trigger_deprecation('symfony/security-core', '5.3', 'Returning a string from "getSalt()" without implementing the "%s" interface is deprecated, the "%s" class should implement it.', LegacyPasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        $hasher = $this->hasherFactory->getPasswordHasher($user);

        if (null === $user->getPassword() || !$hasher->verify($user->getPassword(), $password, $user->getSalt())) {
            $this->context->addViolation($constraint->message);
        }
    }
}
