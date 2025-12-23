<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth\Interfaces;

/** @api */
interface Permission extends \Stringable
{
  #[\NoDiscard]
  public function matches(string $permission): bool;
}
