<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    private $users = [
        [
            'id' => 1,
            'username' => 'Alice',
            'logins' => [
                ['datetime' => '2024-07-17T15:24:00'],
                ['datetime' => '2024-07-16T14:23:00'],
                ['datetime' => '2024-07-15T13:22:00'],
            ],
        ],
        [
            'id' => 2,
            'username' => 'Bob',
            'logins' => [
                ['datetime' => '2024-07-17T16:24:00'],
                ['datetime' => '2024-07-15T12:22:00'],
            ],
        ]
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
