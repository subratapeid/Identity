<?php

namespace Pagelyne\Identity\Services;

use RuntimeException;

class DataEncryptionService
{
    /**
     * Encrypt a value.
     */
    public function encrypt(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $key = $this->getKey();

        $cipher = config(
            'security.encryption.cipher',
            'aes-256-gcm'
        );

        $ivLength = openssl_cipher_iv_length($cipher);

        $iv = random_bytes($ivLength);

        $tag = '';

        $encrypted = openssl_encrypt(
            $value,
            $cipher,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($encrypted === false) {
            throw new RuntimeException(
                'Unable to encrypt the given value.'
            );
        }

        /*
         * Store everything required for decryption
         * in a single base64 encoded string.
         */
        return base64_encode(
            json_encode([
                'iv' => base64_encode($iv),
                'tag' => base64_encode($tag),
                'data' => base64_encode($encrypted),
            ], JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Decrypt a value.
     */
    public function decrypt(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $key = $this->getKey();

        $cipher = config(
            'security.encryption.cipher',
            'aes-256-gcm'
        );

        try {
            $payload = json_decode(
                base64_decode($value, true),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $iv = base64_decode($payload['iv'], true);
            $tag = base64_decode($payload['tag'], true);
            $encrypted = base64_decode($payload['data'], true);

            $decrypted = openssl_decrypt(
                $encrypted,
                $cipher,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($decrypted === false) {
                throw new RuntimeException(
                    'Unable to decrypt the given value.'
                );
            }

            return $decrypted;

        } catch (\Throwable $e) {
            throw new RuntimeException(
                'Unable to decrypt the given value.',
                previous: $e
            );
        }
    }

    /**
     * Mask a value while keeping visible characters at the beginning
     * and end.
     */
    public function mask(
        ?string $value,
        int $start = 3,
        int $end = 3,
        string $character = '*'
    ): ?string {
        if ($value === null || $value === '') {
            return $value;
        }

        $length = mb_strlen($value);

        /*
         * If the value is too short to safely show both
         * beginning and ending portions, mask the middle.
         */
        if ($length <= ($start + $end)) {
            return str_repeat($character, $length);
        }

        $first = mb_substr($value, 0, $start);

        $last = mb_substr(
            $value,
            $length - $end,
            $end
        );

        $middleLength = $length - $start - $end;

        return $first
            . str_repeat($character, $middleLength)
            . $last;
    }

    /**
     * Encrypt and return the encrypted value.
     */
    public function encryptValue(?string $value): ?string
    {
        return $this->encrypt($value);
    }

    /**
     * Decrypt and mask a value.
     */
    public function decryptAndMask(
        ?string $value,
        int $start = 3,
        int $end = 3,
        string $character = '*'
    ): ?string {
        if ($value === null || $value === '') {
            return $value;
        }

        return $this->mask(
            $this->decrypt($value),
            $start,
            $end,
            $character
        );
    }

    /**
     * Get encryption key.
     */
    protected function getKey(): string
    {
        $key = config('security.encryption.key');

        if (!$key) {
            throw new RuntimeException(
                'DATA_ENCRYPTION_KEY is not configured.'
            );
        }

        $decoded = base64_decode($key, true);

        if ($decoded === false || strlen($decoded) !== 32) {
            throw new RuntimeException(
                'DATA_ENCRYPTION_KEY must be a valid base64 encoded 32-byte key.'
            );
        }

        return $decoded;
    }
}