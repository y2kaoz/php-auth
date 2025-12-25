<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

interface RoleInterface extends \Stringable
{
  #[\NoDiscard]
  public function matches(string $role): bool;
}
