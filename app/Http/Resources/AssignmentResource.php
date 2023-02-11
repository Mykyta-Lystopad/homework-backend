<?php

namespace App\Http\Resources;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin Assignment
 */
class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at->toDateTimeString(),
            'due_date' => optional($this->due_date)->toDateString(),
            'problems' => ProblemResource::collection($this->whenLoaded('problems')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'userAnswer' => $this->when(
                $this->relationLoaded('userAnswer') and $this->userAnswer,
                function () {
                    return AnswerResource::make($this->userAnswer);
                },
                ['attachments' => [], 'messages' => []]
            ),
        ];
    }
}
