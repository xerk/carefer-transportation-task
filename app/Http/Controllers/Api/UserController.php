<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', User::class);

        $search = $request->get('search', '');

        $users = User::search($search)
            ->latest()
            ->paginate();

        return new UserCollection($users);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'profile_photo_path' => ['image', 'max:1024', 'nullable'],
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required'],
            'type' => ['required', 'in:admin,passenger,driver'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_photo_path')) {
            $validated['profile_photo_path'] = $request
                ->file('profile_photo_path')
                ->store('public');
        }

        $user = User::create($validated);

        return new UserResource($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'profile_photo_path' => ['image', 'max:1024', 'nullable'],
            'name' => ['required', 'max:255', 'string'],
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($user),
                'email',
            ],
            'password' => ['nullable'],
            'type' => ['required', 'in:admin,passenger,driver'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_photo_path')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }

            $validated['profile_photo_path'] = $request
                ->file('profile_photo_path')
                ->store('public');
        }

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if ($user->profile_photo_path) {
            Storage::delete($user->profile_photo_path);
        }

        $user->delete();

        return response()->noContent();
    }
}
