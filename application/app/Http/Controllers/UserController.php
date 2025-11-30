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
            ->orderByDesc('updated_at')
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

    public function create(Request $request)
    {
        return Inertia::render('UserForm', [
            'title' => 'Add new user'
        ]);
    }

    public function store(Request $request)
    {
        // could be moved to separate request class
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', 
            'address.country' => 'nullable|string|max:100',
            'address.city' => 'nullable|string|max:250',
            'address.post_code' => 'nullable|string|max:20',
            'address.address' => 'nullable|string|max:255',
        ]);

        $addressData = $request->input('address');
        $userData = $request->except('address');

        $user = User::create($userData);
        $user->address()->create([
            'country' => $addressData['country'] ?? '',
            'city' => $addressData['city'] ?? '',
            'address' => $addressData['address'] ?? '',
            'post_code' => $addressData['post_code'] ?? '',
        ]);

        return redirect()->route('users.index');
    }

    public function edit(int $id)
    {
        $user = User::with(['address'])->find($id)->toArray();

        return Inertia::render('UserForm', [
            'title' => 'Edit user',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address.country' => 'nullable|string|max:100',
            'address.city' => 'nullable|string|max:250',
            'address.post_code' => 'nullable|string|max:20',
            'address.address' => 'nullable|string|max:255',
        ]);

        $addressData = $request->input('address');
        $userData = $request->except('address');

        $user->update($userData);
        $user->address()->update([
            'country' => $addressData['country'] ?? '',
            'city' => $addressData['city'] ?? '',
            'address' => $addressData['address'] ?? '',
            'post_code' => $addressData['post_code'] ?? '',
        ]);
        $user->touch(); // trigger updating updated_at if only address updated

        return redirect()->route('users.index');
    }
}
