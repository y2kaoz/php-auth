<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

interface RbacAuthorizationInterface extends AuthorizationInterface
{
  #[\NoDiscard]
  public function hasRole(string $role): bool;

  /** @param list<string> $roles */
  #[\NoDiscard]
  public function hasAllRoles(array $roles): bool;

  /** @param list<string> $roles */
  #[\NoDiscard]
  public function hasAnyRoles(array $roles): bool;
}
