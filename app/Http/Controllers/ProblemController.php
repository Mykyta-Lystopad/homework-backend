<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProblemRequest;
use App\Http\Resources\ProblemResource;
use App\Models\Problem;
use Exception;
use Illuminate\Http\JsonResponse;

class ProblemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ProblemRequest $request
     * @return JsonResponse
     */
    public function store(ProblemRequest $request)
    {
        $problem = Problem::create($request->validated());
        return $this->created(ProblemResource::make($problem));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProblemRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function update(ProblemRequest $request, Problem $problem)
    {
        $problem->update($request->validated());
        return $this->success(ProblemResource::make($problem));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Problem $problem
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Problem $problem)
    {
        $problem->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
