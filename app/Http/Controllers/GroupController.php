<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupResourceCollection;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function myGroups()
    {
        $groups = request()->user()->myGroups()->paginate();
        return $this->success(GroupResourceCollection::make($groups));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function userGroups()
    {
        $groups = auth()->user()->userGroups;
        return $this->success(GroupResource::collection($groups));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return JsonResponse
     */
    public function store(GroupRequest $request)
    {
        $group = Group::create($request->validated());
        return $this->created(GroupResource::make($group));
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function show(Group $group)
    {
        return $this->success(GroupResource::make($group->load('users')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupRequest $request
     * @param Group $group
     * @return JsonResponse
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->validated());
        return $this->success(GroupResource::make($group));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Bind Qr Code
     *
     * @param string $code
     * @return JsonResponse
     */
    public function bindGroup(string $code)
    {
        /** @var User $user */
        $user = auth()->user();
        $data = (int)(new Hashids(config('app.name')))->decodeHex($code);

        if ($data) {
            $group = Group::findOrFail($data);
            $user->userGroups()->syncWithoutDetaching($group);
            return $this->success(GroupResource::make($group));
        }
        return $this->error('Wrong code.');
    }

    /**
     * Add student to group by code
     *
     * @param Group $group
     * @param string $code
     * @return JsonResponse
     */
    public function bindStudentByCode(Group $group, string $code)
    {
        $userId = (int)(new Hashids(config('app.name')))->decodeHex($code);

        if ($userId) {
            $student = User::findOrFail($userId);
            $student->userGroups()->syncWithoutDetaching($group);
            return $this->success(UserResource::make($student));
        }
        return $this->error('Wrong code.');
    }

    /**
     * Remove student from group by id
     *
     * @param Group $group
     * @param int $studentId
     * @return JsonResponse
     */
    public function removeStudent(Group $group, int $studentId)
    {
        if ($group->users()->detach($studentId)) {
            return $this->success('User removed from group.');
        }
        return $this->error('User no in this group.');
    }
}
