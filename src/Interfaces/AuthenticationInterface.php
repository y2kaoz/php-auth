<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

interface AuthenticationInterface
{
  #[\NoDiscard]
  public function isAuthenticated(): bool;
}
