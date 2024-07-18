<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    private $users = [
        [
            'id' => 5,
            'username' => 'Test 5',
            'logins' => [
                ['datetime' => '2023-12-21 18:29'],
            ],
        ],
        [
            'id' => 4,
            'username' => 'Test 4',
            'logins' => [
                ['datetime' => '2023-12-21 18:27'],
            ],
        ],
        [
            'id' => 42,
            'username' => 'Test 42',
            'logins' => [
                ['datetime' => '2023-12-21 18:24'],
            ],
        ],
        [
            'id' => 22,
            'username' => 'Test 22',
            'logins' => [
                ['datetime' => '2023-12-21 18:20'],
            ],
        ],
        [
            'id' => 829,
            'username' => 'Test 829',
            'logins' => [
                ['datetime' => '2023-12-21 18:15'],
            ],
        ],
        [
            'id' => 15,
            'username' => 'Test 15',
            'logins' => [
                ['datetime' => '2023-12-21 18:09'],
            ],
        ],
        [
            'id' => 14,
            'username' => 'Test 14',
            'logins' => [
                ['datetime' => '2023-12-21 18:02'],
            ],
        ],
        [
            'id' => 142,
            'username' => 'Test 142',
            'logins' => [
                ['datetime' => '2023-12-21 17:54'],
            ],
        ],
        [
            'id' => 122,
            'username' => 'Test 122',
            'logins' => [
                ['datetime' => '2023-12-21 17:45'],
            ],
        ],
        [
            'id' => 1829,
            'username' => 'Test 1829',
            'logins' => [
                ['datetime' => '2023-12-21 17:35'],
            ],
        ],
    ];

    private function getLastLogin($user)
    {
        return collect($user['logins'])->pluck('datetime')->map(function ($datetime) {
            return Carbon::parse($datetime);
        })->max();
    }

    public function getUsers()
    {
        $userData = collect($this->users)->map(function ($user) {
            $lastLogin = $this->getLastLogin($user);
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'lastLoginAt' => $lastLogin->format('Y-m-d H:i'),
                'totalLogins' => count($user['logins']),
            ];
        });

        $sortedUsers = $userData->sortByDesc('lastLoginAt')->take(10)->values();

        return response()->json($sortedUsers);
    }
}
