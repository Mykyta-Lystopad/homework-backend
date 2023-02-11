<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignmentRequest;
use App\Http\Requests\Admin\UserAssignmentRequest;
use App\Http\Resources\Admin\AssignmentResource;
use App\Http\Resources\Admin\AssignmentResourceCollection;
use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = request()->user();
        $group = Group::findOrFail(request()->group_id);
        $assignmentsQuery = $group->assignments()->getQuery();
        if ($user->role === User::ROLE_STUDENT or request()->has('user_id')) {
            $assignmentsQuery->with('problems.userSolution');
        } else {
            $assignmentsQuery->with('problems');
        }
        $assignments = $assignmentsQuery->latest()->paginate()->appends('group_id', $group->id);
        return $this->success(AssignmentResourceCollection::make($assignments));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AssignmentRequest $request
     * @return JsonResponse
     */
    public function store(AssignmentRequest $request)
    {
        $assignment = Assignment::create($request->validated());
        if ($request->has('attachments')) {
            $assignment->attachments()->sync($request->attachments);
        }
        if ($request->has('problems')) {
            $assignment->problems()->createMany($request->problems);
        }

        return $this->created(AssignmentResource::make($assignment->load(['attachments', 'problems'])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserAssignmentRequest $request
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function userAssignments(UserAssignmentRequest $request, Assignment $assignment)
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Answer $userAnswer */
        $userAnswer = $assignment->answers()->firstOrCreate(['user_id' => $user->id]);
        $userAnswer->attachments()->sync($request->attachments);

        return $this->success(AssignmentResource::make($assignment->load([
            'attachments',
            'problems.userSolution',
            'userAnswer.attachments',
            'userAnswer.messages.user'
        ])));
    }

    /**
     * Display the specified resource.
     *
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function show(Assignment $assignment)
    {
        /** @var User $user */
        $user = request()->user();
        if ((request()->has('user_id'))
            or $user->role === User::ROLE_STUDENT) {
            $assignment->load([
                'problems.userSolution',
                'attachments',
                'userAnswer.attachments',
                'userAnswer.messages',
                'userAnswer.messages.user'
            ]);
        } else {
            $assignment->load([
                'problems',
                'attachments'
            ]);
        }
        return $this->success(AssignmentResource::make($assignment));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AssignmentRequest $request
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function update(AssignmentRequest $request, Assignment $assignment)
    {
        $assignment->update($request->validated());
        if ($request->has('attachments')) {
            $assignment->attachments()->sync($request->attachments);
        }

        return $this->success(AssignmentResource::make($assignment->load(['attachments', 'problems'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Assignment $assignment
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
