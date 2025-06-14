<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'web_nama' => $this->web_nama,
            'web_logo' => asset('storage/' . $this->web_logo),
            'web_deskripsi' => $this->web_deskripsi,
            'user' => [
                'id' => $this->user['id'],
                'name' => $this->user['name'],
                'email' => $this->user['email'],
            ]
        ];
    }
}
