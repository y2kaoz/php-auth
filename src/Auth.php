<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\Authorization;
use Y2KaoZ\PhpAuth\Interfaces\Auth as AuthInterface;
use Y2KaoZ\PhpAuth\Interfaces\Authentication;

/** @api */
final class Auth implements AuthInterface
{
  public function __construct(
    private(set) Authentication $authentication,
    private(set) Authorization $authorization
  ) {}

  #[\Override]
  public function isAuthenticated(): bool
  {
    return $this->authentication->isAuthenticated();
  }

  #[\Override]
  public function isGranted(string $permission): bool
  {
    return $this->authorization->isGranted($permission);
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function allGranted(array $permissions): bool
  {
    return $this->authorization->allGranted($permissions);
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function anyGranted(array $permissions): bool
  {
    return $this->authorization->anyGranted($permissions);
  }
}
