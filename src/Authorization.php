<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\AuthorizationInterface;
use Y2KaoZ\PhpAuth\Interfaces\PermissionInterface;

/** 
 * @api 
 * @template PermissionT of PermissionInterface
 */
class Authorization implements AuthorizationInterface
{
  /** @var null|list<PermissionT> */
  public null|array $permissions = null {
    get {
      if (is_null($this->permissions)) {
        $list = [];
        if (is_array($this->storage[$this->permissionKey] ?? null)) {
          $list = array_values(array_filter(
            $this->storage[$this->permissionKey],
            fn($p) => $p instanceof PermissionInterface
          ));
        }
        $this->permissions = $list;
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
