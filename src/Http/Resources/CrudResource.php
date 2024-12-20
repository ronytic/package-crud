<?php

namespace ProcessMaker\Package\PackageCrud\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CrudResource extends JsonResource
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
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
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
