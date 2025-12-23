<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\Authorization as AuthorizationInterface;
use Y2KaoZ\PhpAuth\Interfaces\Permission;

/** @api */
final class Authorization implements AuthorizationInterface
{
  /** @var null|list<Permission> */
  public null|array $permissions = null {
    get {
      if (is_null($this->permissions)) {
        $permissions = [];
        if (isset($this->storage[$this->permissionKey]) && is_array($this->storage[$this->permissionKey])) {
          $permissions = array_values(array_filter(
            $this->storage[$this->permissionKey],
            fn($permission) => $permission instanceof Permission
          ));
        }
        $this->permissions = $permissions;
      }
      return $this->permissions;
    }
    set(null|array $values) {
      if($values===null) {
        unset($this->storage[$this->permissionKey]);
        $this->permissions = null;
        return;
      }
      $this->permissions = $this->storage[$this->permissionKey] = $values;
    }
  }

  /** @param array<string,mixed>|\ArrayAccess<string,mixed> $storage */
  public function __construct(
    private(set) array|\ArrayAccess &$storage,
    private(set) readonly string $permissionKey = 'permissions'
  ) {
  }

  #[\Override]
  public function isGranted(string $permission): bool
  {
    return array_any($this->permissions ?? [], fn($item) => $item->matches($permission));
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function allGranted(array $permissions): bool
  {
    return array_all($permissions, fn($permission) => $this->isGranted($permission));
  }

  /** @param list<string> $permissions */
  #[\Override]
  public function anyGranted(array $permissions): bool
  {
    return array_any($permissions, fn($permission) => $this->isGranted($permission));
  }
}
