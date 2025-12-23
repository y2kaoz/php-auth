<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\Permission;

/** @api */
final class FlatPermission implements Permission, \Stringable
{
  private(set) readonly string $value;

  public function __construct(
    string|\Stringable $permission
  ) {
    $this->value = strval($permission);
  }

  #[\Override]
  public function matches(string $permission): bool
  {
    return $this->value === $permission;
  }

  #[\Override]
  public function __toString(): string
  {
    return $this->value;
  }
}
