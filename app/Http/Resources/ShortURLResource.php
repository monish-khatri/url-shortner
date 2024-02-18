<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortURLResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'short_url' => $this->short_url,
            'redirect_url' => $this->redirect_url,
            'clicks' => $this->visits()->count(),
            'is_active' => $this->is_active,
            'track_visits' => $this->track_visits,
            'single_use' => $this->single_use,
        ];
    }
}
