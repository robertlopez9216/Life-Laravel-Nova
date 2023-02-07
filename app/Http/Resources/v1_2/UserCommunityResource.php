<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCommunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'UserCommunity',
            'recipes' => BasicResource::collection($this->whenLoaded('recipes')),
            'remedies' => BasicResource::collection($this->whenLoaded('remedies')),
        ];
    }
}
