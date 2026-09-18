<?php

declare(strict_types=1);

namespace Identity\Api;

class UserApi
{
    public function paginate(array $filters = []): array
    {
        return [
            'success' => true,

            'message' => 'Users fetched successfully.',

            'data' => [

                [
                    'id' => 1,
                    'uuid' => '6d4d25a4-4fcb-4dd0-a0d2-7d52f7d60001',
                    'first_name' => 'Subrata',
                    'last_name' => 'Roy',
                    'full_name' => 'Subrata Roy',
                    'email' => 'subrata@example.com',
                    'phone' => '9876543210',
                    'avatar' => null,
                    'is_active' => true,
                    'roles' => [
                        'Super Admin',
                    ],
                    'created_at' => '2026-08-05T10:30:00+05:30',
                    'updated_at' => '2026-08-05T10:30:00+05:30',
                ],

                [
                    'id' => 2,
                    'uuid' => '8ab4c6d8-f4a2-4d78-9a20-4fdbdcb10002',
                    'first_name' => 'Rahul',
                    'last_name' => 'Sharma',
                    'full_name' => 'Rahul Sharma',
                    'email' => 'rahul@example.com',
                    'phone' => '9123456780',
                    'avatar' => null,
                    'is_active' => false,
                    'roles' => [
                        'Manager',
                    ],
                    'created_at' => '2026-08-04T15:45:00+05:30',
                    'updated_at' => '2026-08-05T09:15:00+05:30',
                ],

            ],

            'meta' => [
                'current_page' => 1,
                'per_page' => 15,
                'total' => 2,
                'last_page' => 1,
            ],
        ];
    }

    public function find(int $id): ?array
    {
        foreach ($this->paginate()['data'] as $user) {

            if ($user['id'] === $id) {
                return $user;
            }

        }

        return null;
    }
}