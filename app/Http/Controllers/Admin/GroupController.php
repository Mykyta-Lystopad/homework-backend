<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GroupRequest;
use App\Http\Resources\Admin\GroupResource;
use App\Http\Resources\Admin\GroupResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $groups = Group::with(['user','subject'])->paginate();
        return $this->success(GroupResourceCollection::make($groups));
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
        return $this->created(GroupResource::make($group->load('subject')));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function show(Group $group)
    {
        return $this->success(GroupResource::make($group->load(['users', 'user', 'subject','assignments','assignments.problems'])));
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
        return $this->success(GroupResource::make($group->load(['user', 'subject'])));
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
}
