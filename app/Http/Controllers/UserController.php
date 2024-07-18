<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    private $users = [
        [
            'user_id' => 1,
            'name' => 'Alice',
            'logins' => [
                ['datetime' => '2024-07-17T15:24:00'],
                ['datetime' => '2024-07-16T14:23:00'],
                ['datetime' => '2024-07-15T13:22:00'],
            ],
        ],
        [
            'user_id' => 2,
            'name' => 'Bob',
            'logins' => [
                ['datetime' => '2024-07-17T16:24:00'],
                ['datetime' => '2024-07-15T12:22:00'],
            ],
        ],
        // Add more users as needed
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
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'last_login' => $lastLogin->toIso8601String(),
                'total_logins' => count($user['logins']),
            ];
        });

        $sortedUsers = $userData->sortByDesc('last_login')->take(10)->values();

        return response()->json($sortedUsers);
    }
}
