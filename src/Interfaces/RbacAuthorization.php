<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

/** @api */
interface RbacAuthorization extends Authorization
{
  public function hasRole(string $role): bool;

  /** @param list<string> $roles */
  public function hasAllRoles(array $roles): bool;

  /** @param list<string> $roles */
  public function hasAnyRoles(array $roles): bool;
}
