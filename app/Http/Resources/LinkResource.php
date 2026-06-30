<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'href' => $this->href,
            'sref' => $this->sref,
            'short_url' => url("/l/{$this->sref}"),
            'created_at' => $this->created_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
            'clicks_count' => $this->whenCounted('logs'),
        ];
    }
}