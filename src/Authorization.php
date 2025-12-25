<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\Authorization as AuthorizationInterface;
use Y2KaoZ\PhpAuth\Interfaces\Permission;

/** 
 * @api 
 * @template PermissionT of Permission
 */
class Authorization implements AuthorizationInterface
{
  /** @var null|list<PermissionT> */
  public null|array $permissions = null {
    get {
      if (is_null($this->permissions)) {
        $permissions = [];
        if (is_array($this->storage[$this->permissionKey] ?? null)) {
          $permissions = array_values(array_filter(
            $this->storage[$this->permissionKey],
            fn($permission) => !is_null($permission) && $permission instanceof Permission
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

  /** @param \ArrayAccess<array-key,mixed>|array<array-key,mixed> $storage */
  public function __construct(
    protected(set) array|\ArrayAccess &$storage,
    protected(set) readonly string $permissionKey = 'permissions'
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
