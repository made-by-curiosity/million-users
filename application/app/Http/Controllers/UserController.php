<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StringService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request, StringService $stringService)
    {
        $searchText = $request->get('search', '');
        $sanitizedSearch = $stringService->sanitizeBooleanInput($searchText);

        $users = User::with('address')
            ->search($sanitizedSearch)
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->toArray();

        return Inertia::render('User', [
            'users' => $users['data'],
            'total' => $users['total'],
            'currentPage' => $users['current_page'],
            'links' => $users['links'],
            'from' => $users['from'],
            'to' => $users['to'],
            'searchText' => $searchText ?? '',
        ]);
    }
}
