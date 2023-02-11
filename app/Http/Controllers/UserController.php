<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display user info.
     *
     * @return JsonResponse
     */
    public function profileShow()
    {
        return $this->success(UserResource::make(auth()->user()));
    }

    /**
     * Update user info.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function profileUpdate(UserRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->update($request->validated());
        return $this->success(UserResource::make($user));
    }

    /**
     * Get user parents.
     *
     * @return JsonResponse
     */
    public function myParents()
    {
        $parents = auth()->user()->parents;
        return $this->success(UserResource::collection($parents));
    }

    /**
     * Get user children.
     *
     * @return JsonResponse
     */
    public function myChildren()
    {
        $children = request()->user()->children;
        $children->load('userGroups');
        return $this->success(UserResource::collection($children));
    }

    /**
     * Bind Qr Code
     *
     * @param string $code
     * @return JsonResponse
     */
    public function bindChild(string $code)
    {
        /** @var User $user */
        $user = auth()->user();
        $data = (int)(new Hashids(config('app.name')))->decodeHex($code);

        $message = 'Wrong code.';
        if ($data) {
            $child = User::findOrFail($data);
            $user->children()->syncWithoutDetaching($child);
            $message = 'User attached as Child.';
        }

        return $this->success($message);
    }
}
