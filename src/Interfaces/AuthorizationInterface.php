<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

interface AuthorizationInterface
{
  #[\NoDiscard]
  public function isGranted(string $permission): bool;

  /** @param list<string> $permissions */
  #[\NoDiscard]
  public function allGranted(array $permissions): bool;

  /** @param list<string> $permissions */
  #[\NoDiscard]
  public function anyGranted(array $permissions): bool;
}
