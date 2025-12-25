<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\RoleInterface;

/** 
 * @api 
 */
final class FlatRole implements RoleInterface
{
  private(set) readonly string $value;

  public function __construct(
    string|\Stringable $role
  ) {
    $this->value = strval($role);
  }

  #[\Override]
  public function matches(string $role): bool
  {
    return $this->value === $role;
  }

  #[\Override]
  public function __toString(): string
  {
    return $this->value;
  }
}
