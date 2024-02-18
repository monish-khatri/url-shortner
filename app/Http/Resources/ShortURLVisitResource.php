<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortURLVisitResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'short_url_id' => $this->short_url_id,
            'ip_address' => $this->ip_address ?? '-',
            'operating_system' => $this->operating_system ?? '-',
            'operating_system_version' => $this->operating_system_version ?? '-',
            'browser' => $this->browser ?? '-',
            'browser_version' => $this->browser_version ?? '-',
            'visited_at' => $this->visited_at ?? '-',
            'referer_url' => $this->referer_url ?? '-',
            'device_type' => $this->device_type ?? '-',
        ];
    }
}
