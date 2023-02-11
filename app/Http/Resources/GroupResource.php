<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin Group
 */
class GroupResource extends JsonResource
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
            'note' => $this->note,
            'subject' => $this->subject->title,
            'model_code' => $this->model_code,
            'qr_code_link' => $this->qr_code_link,
            'students' => UserResource::collection($this->whenLoaded('users')),
            'teacher' => UserResource::make($this->whenLoaded('user')),
            'count_students' => $this->users()->count(),
        ];
    }
}
