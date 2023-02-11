<?php

namespace App\Http\Resources\Admin;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AnswerResource
 * @package App\Http\Resources
 * @mixin Answer
 */
class AnswerResource extends JsonResource
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
            'assignment' => AssignmentResource::make($this->whenLoaded('assignment')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
