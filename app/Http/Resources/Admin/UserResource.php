<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'model_code' => $this->model_code,
            'qr_code_link' => $this->qr_code_link,
            'userGroups' => GroupResource::collection($this->whenLoaded('userGroups')),
            'myGroups' => GroupResource::collection($this->whenLoaded('myGroups')),
            'avatar' => optional($this->avatar)->file_link,
            'phone' => $this->phone,
            'myAssignments' => AssignmentResource::collection($this->whenLoaded('myAssignments')),
            'myAnswers' => AnswerResource::collection($this->whenLoaded('myAnswers')),
        ];
    }
}
