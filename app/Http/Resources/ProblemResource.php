<?php

namespace App\Http\Resources;

use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin Problem
 */
class ProblemResource extends JsonResource
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
            'userSolution' => SolutionResource::make($this->whenLoaded('userSolution'))
        ];
    }
}

