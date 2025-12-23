<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Authorization;
use Y2KaoZ\PhpAuth\Interfaces\Role;
use Y2KaoZ\PhpAuth\Interfaces\RbacAuthorization as RbacAuthorizationInterface;

/** @api */
final class RbacAuthorization extends Authorization implements RbacAuthorizationInterface
{
  /** @var null|list<Role> */
  public null|array $roles = null {
    get {
      if (is_null($this->roles) && isset($this->storage[$this->roleKey])) {
        $roles = [];
        if (is_array($this->storage[$this->roleKey])) {
          $roles = array_values(array_filter(
            $this->storage[$this->roleKey],
            fn($role) => $role instanceof Role
          ));
        }
        $this->roles = $roles;
      }
      return $this->roles;
    }
    set(null|array $values) {
      if ($values === null) {
        unset($this->storage[$this->roleKey]);
        $this->roles = null;
        return;
      }
      $this->roles = $this->storage[$this->roleKey] = $values;
    }
  }

  /** @param \ArrayAccess<array-key,mixed>|array<array-key,mixed> $storage */
  public function __construct(
    protected(set) array|\ArrayAccess &$storage,
    protected(set) readonly string $permissionKey = 'permissions',
    protected(set) readonly string $roleKey = 'role',
  ) {
    parent::__construct($storage, $permissionKey);
  }

  #[\Override]
  public function hasRole(string $role): bool
  {
    return array_any($this->roles ?? [], fn($item) => $item->matches($role));
  }

  /** @param list<string> $roles */
  #[\Override]
  public function hasAllRoles(array $roles): bool
  {
    return array_all($roles, fn($role) => $this->isGranted($role));
  }

  /** @param list<string> $roles */
  #[\Override]
  public function hasAnyRoles(array $roles): bool
  {
    return array_any($roles, fn($role) => $this->isGranted($role));
  }
}
