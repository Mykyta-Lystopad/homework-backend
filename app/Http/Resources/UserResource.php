<?php

namespace App\Http\Resources;

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
        $apiToken = $request->routeIs('auth.*') ? $this->user_token : null;
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'model_code' => $this->model_code,
            'qr_code_link' => $this->qr_code_link,
            'api_token' => $this->when($apiToken, $apiToken),
            'userGroups' => GroupResource::collection($this->whenLoaded('userGroups')),
            'avatar' => optional($this->avatar)->file_link,
            'phone' => $this->phone,
        ];
    }
}
