<?php

namespace App\Security\Key;

use Symfony\Component\HttpFoundation\RequestStack;

class ApiKeyManager
{

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly string       $appSecret
    )
    {
    }

    public function isValidKey(Key $key): bool
    {
        return $this->calculateHashForKey($key) === $key->hash;
    }

    public function createKeyFromGivenString(string $givenString = null): Key
    {
        $key = new Key();
        if (!empty($givenString)) {
            [$key->id, $key->salt, $key->hash] = explode(Key::SEPARATOR, $givenString);
        }

        return $key;
    }

    public function createKeyFromValues(string $id = '', string $salt = null): Key
    {
        $key = Key::create()->setId($id);
        $key->setSalt($salt);

        return $key;
    }

    public function createCodeStringForKey(Key $key): string
    {
        $key->hash = $this->calculateHashForKey($key);

        return implode(Key::SEPARATOR, [$key->id, $key->salt, $key->hash]);
    }

    public function calculateHashForKey(Key $key): string
    {
        $hashables = [$key->id, $key->salt, $this->appSecret];

        return self::createHashFromString(implode(Key::SEPARATOR, $hashables));
    }

    public static function createHashFromString(string $string): string
    {
        return substr(hash('sha256', $string), 0, 64);
    }

    public function getKeyCodeFromRequest(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        $keyCode = $request?->query->get('key');
        $keyCode ??= $request?->request->get('key');
        $keyCode ??= $request?->cookies->get('key');

        return $keyCode;
    }

}