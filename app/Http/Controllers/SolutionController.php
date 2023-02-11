<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetMarkRequest;
use App\Http\Requests\SolutionRequest;
use App\Http\Resources\SolutionResource;
use App\Http\Resources\AnswerResourceCollection;
use App\Models\Answer;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SolutionController extends Controller
{
    /**
     * Display a listing of current user resources.
     *
     * @return JsonResponse
     */
    public function myAnswers()
    {
        $solutions = request()->user()->myAnswers()->latest()->with(['assignment.problems.userSolution'])->paginate();
        return $this->success(AnswerResourceCollection::make($solutions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolutionRequest $request
     * @return JsonResponse
     */
    public function store(SolutionRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Answer $userAnswer */
        $userAnswer = Problem::find(request()->problem_id)->assignment->answers()->firstOrCreate(['user_id' => $user->id]);
        $solution = Solution::make($request->validated());
        $userAnswer->solutions()->save($solution);

        return $this->created(SolutionResource::make($solution));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolutionRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function update(SolutionRequest $request, Solution $solution)
    {
        $solution->update($request->validated());
        return $this->success(SolutionResource::make($solution));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SetMarkRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function setMark(SetMarkRequest $request, Solution $solution)
    {
        $solution->update($request->validated());
        return $this->success(SolutionResource::make($solution));
    }
}
