<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

/** @api */
interface Authentication
{
  public function isAuthenticated(): bool;
}
