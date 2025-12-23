<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpAuth;

use Y2KaoZ\PhpAuth\Interfaces\Authentication;

/** @api */
final class PasswordAuthentication implements Authentication
{
  const string|int|null PasswordAlgorithm = PASSWORD_DEFAULT;

  /** @var false|non-falsy-string */
  private(set) false|string $passwordNeedsUpdate = false;

  private(set) null|string $username = null {
    get {
      if ($this->username === null && isset($this->storage[$this->usernameKey])) {
        $username = $this->storage[$this->usernameKey] ?? null;
        assert(!isset($username) || is_string($username));
        $this->username = $username;
      }
      return $this->username;
    }
    set(null|string $value) {
      if ($value === null) {
        unset($this->storage[$this->usernameKey]);
        $this->username = null;
        return;
      }
      $this->username = $this->storage[$this->usernameKey] = $value;
    }
  }

  /** @param \ArrayAccess<array-key,mixed>|array<array-key,mixed> $storage */
  public function __construct(
    private(set) array|\ArrayAccess &$storage,
    private(set) readonly string $usernameKey = 'username'
  ) {}

  #[\NoDiscard()]
  public function login(string $username, string $password, string $hash): bool
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
      $this->username = $username;
      return true;
    }
    $this->username = null;
    return false;
  }

  public function logout(): void
  {
    $this->username = null;
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_destroy();
    }
  }

  #[\Override]
  public function isAuthenticated(): bool
  {
    return isset($this->storage[$this->usernameKey]);
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
