<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\RbacAuthorization;
use Y2KaoZ\PhpAuth\Interfaces\RbacAuth as RbacAuthInterface;
use Y2KaoZ\PhpAuth\Interfaces\Authentication;

/** @api */
final class RbacAuth implements RbacAuthInterface
{
  public function __construct(
    private(set) Authentication $authentication,
    private(set) RbacAuthorization $authorization
  ) {}

  #[\Override]
  public function isAuthenticated(): bool
  {
    return $this->authentication->isAuthenticated();
  }

  #[\Override]
  public function isGranted(string $permission): bool
  {
    return $this->authorization->isGranted($permission);
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function allGranted(array $permissions): bool
  {
    return $this->authorization->allGranted($permissions);
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function anyGranted(array $permissions): bool
  {
    return $this->authorization->anyGranted($permissions);
  }

  #[\Override]
  public function hasRole(string $role): bool
  {
    return $this->authorization->hasRole($role);
  }

  /** @param list<string> $roles */
  #[\Override]
  public function hasAllRoles(array $roles): bool
  {
    return $this->authorization->hasAllRoles($roles);
  }

  /** @param list<string> $roles */
  #[\Override]
  public function hasAnyRoles(array $roles): bool
  {
    return $this->authorization->hasAnyRoles($roles);
  }
}
