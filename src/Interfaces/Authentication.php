<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

/** @api */
interface Authentication
{
  #[\NoDiscard]
  public function isAuthenticated(): bool;
}
