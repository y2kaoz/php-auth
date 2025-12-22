<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

/** @api */
interface Authorization
{
  public function isGranted(string $permission): bool;

  /** @param list<string> $permissions */
  public function allGranted(array $permissions): bool;

  /** @param list<string> $permissions */
  public function anyGranted(array $permissions): bool;
}
