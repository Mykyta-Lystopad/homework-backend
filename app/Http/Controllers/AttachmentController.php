<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\AttachmentResourceCollection;
use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Solution;
use Exception;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    /**
     * Display a listing of current user resources.
     *
     * @return JsonResponse
     */
    public function myAttachments()
    {
        $attachments = auth()->user()->myAttachments()->paginate();
        return $this->success(AttachmentResourceCollection::make($attachments));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (request()->has('solution_id')) {
            $solution = Solution::findOrFail(request()->solution_id);
            $attachments = $solution->attachments()->with('user')->paginate()->appends('solution_id', $solution->id);
        } elseif (request()->has('assignment_id')) {
            $assignment = Assignment::findOrFail(request()->assignment_id);
            $attachments = $assignment->attachments()->with('user')->paginate()->appends('assignment_id', $assignment->id);
        } else {
            return $this->error('Specify parent model.');
        }
        return $this->success(AttachmentResourceCollection::make($attachments));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AttachmentRequest $request
     * @return JsonResponse
     */
    public function store(AttachmentRequest $request)
    {
        $attachment = Attachment::create($request->validated());
        $attachment->saveFile($request->file_content);
        return $this->created(AttachmentResource::make($attachment));
    }

    /**
     * Display the specified resource.
     *
     * @param Attachment $attachment
     * @return JsonResponse
     */
    public function show(Attachment $attachment)
    {
        return $this->success(AttachmentResource::make($attachment->load('user')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AttachmentRequest $request
     * @param Attachment $attachment
     * @return JsonResponse
     */
    public function update(AttachmentRequest $request, Attachment $attachment)
    {
        $attachment->deleteFile();
        $attachment->update($request->validated());
        $attachment->touch();
        $attachment->saveFile($request->file_content);
        return $this->success(AttachmentResource::make($attachment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attachment $attachment
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
