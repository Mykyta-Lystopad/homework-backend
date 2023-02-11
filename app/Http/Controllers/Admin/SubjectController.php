<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubjectRequest;
use App\Http\Resources\Admin\SubjectResource;
use App\Http\Resources\Admin\SubjectResourceCollection;
use App\Models\Subject;
use Exception;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $subjects = Subject::paginate();
        return $this->success(SubjectResourceCollection::make($subjects));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubjectRequest $request
     * @return JsonResponse
     */
    public function store(SubjectRequest $request)
    {
        $subject = Subject::create($request->validated());
        return $this->created(SubjectResource::make($subject));
    }
    /**
     * Display the specified resource.
     *
     * @param Subject $subject
     * @return JsonResponse
     */
    public function show(Subject $subject)
    {
        return $this->success(SubjectResource::make($subject));
    }
    /**
     * Update the specified resource in storage.
     * @param SubjectRequest $request
     * @param Subject $subject
     * @return JsonResponse
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return $this->success(SubjectResource::make($subject));
    }
    /**
     * Remove the specified resource from storage.
     * @param Subject $subject
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return $this->success(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
