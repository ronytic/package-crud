<?php

namespace ProcessMaker\Package\PackageCrud\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CrudCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "data" => $this->collection->map(function ($item) {
                return new CrudResource($item);
            }),
            "meta" => [
                'filter' => htmlentities($request->input('filter', '')),
                'sort_by' => $request->input('order_by', ''),
                'sort_order' => $request->input('order_direction', ''),
                /**
                 * count: (integer, total items in current response)
                 */
                'count' => $this->resource->count(),
                /**
                 * total_pages: (integer, the total number of pages available, based on per_page and total)
                 */
                'total_pages' => ceil($this->resource->total() / $this->resource->perPage()),
            ]
        ];
    }
    public function with($request)
    {
        return [
            'status' => 'success',
            'message' => 'The request was has been processed successfully'
        ];
    }
}
