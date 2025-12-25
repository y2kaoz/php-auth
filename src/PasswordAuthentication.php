<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use ArrayAccess;
use Y2KaoZ\PhpAuth\Interfaces\AuthenticationInterface;

/** 
 * @api
 * @template UserT
 */
class PasswordAuthentication implements AuthenticationInterface
{
  const string|int|null PasswordAlgorithm = PASSWORD_DEFAULT;

  /** @var false|non-falsy-string */
  protected(set) false|string $passwordNeedsUpdate = false;

  /** @var null|UserT */
  protected(set) mixed $user = null {
    get {
      if ($this->user === null && $this->isAuthenticated()) {
        $this->user = $this->storage[$this->userKey];
      }
      return $this->user;
    }
    set(mixed $value) {
      if ($value === null) {
        $this->user = null;
        unset($this->storage[$this->userKey]);
        return;
      }
      $this->user = $this->storage[$this->userKey] = $value;
    }
  }

  /** @param \ArrayAccess<array-key,mixed>|array<array-key,mixed> $storage */
  public function __construct(
    protected(set) array|\ArrayAccess &$storage,
    protected(set) readonly string $userKey = 'user'
  ) {}

  /** @param UserT $user */
  #[\NoDiscard()]
  public function login(mixed $user, string $password, string $hash): bool
  {
    if (password_verify($password, $hash)) {
      if (password_needs_rehash($hash, self::PasswordAlgorithm)) {
        $newHash = password_hash($password, self::PasswordAlgorithm);
        assert(password_verify($password, $newHash));
        $this->passwordNeedsUpdate = $newHash ?: false;
      }
      if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
      }
      $this->user = $user;
      return true;
    }
    $this->user = null;
    return false;
  }

  public function logout(): void
  {
    $this->user = null;
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_destroy();
    }
  }

  #[\Override]
  public function isAuthenticated(): bool
  {
    if($this->storage instanceof ArrayAccess) {
      return $this->storage->offsetExists($this->userKey);
    }
    return array_key_exists($this->userKey, $this->storage);
  }

  public static function GenerateRandomPassword(int $passwordLength = 16): string
  {
    $chars = "";
    for ($i = 33; $i < 127; $i++) {
      $chars .= chr($i);
    }
    $strlenChars = strlen($chars) - 1;
    $password = '';
    for ($i = 0; $i < $passwordLength; $i++) {
      $password .= $chars[random_int(0, $strlenChars)];
    }
    return $password;
  }
}
