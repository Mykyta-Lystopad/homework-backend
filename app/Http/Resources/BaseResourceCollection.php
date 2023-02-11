<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BaseResourceCollection
 * @package App\Http\Resources
 * @mixin LengthAwarePaginator
 */
class BaseResourceCollection extends ResourceCollection
{
    /**
     * Pagination metadata.
     *
     * @var array
     */
    protected $meta;

    /**
     * Pagination links.
     *
     * @var array
     */
    protected $links;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->links = [
            'first' => $this->url(1),
            'last' => $this->url($this->lastPage()),
            'prev' => $this->previousPageUrl(),
            'next' => $this->nextPageUrl()
        ];

        $this->meta = [
            'count' => $this->count(),
            'current_page' => $this->currentPage(),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total()
        ];

        return [
            'collection' => $this->collection,
            'links' => $this->links,
            'meta' => $this->meta
        ];
    }
}
