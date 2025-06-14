<?php

namespace App\Services;

use App\Http\Resources\GudangResource;
use App\Repositories\GudangRepository;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;

class GudangService
{
    protected $repository;

    public function __construct(GudangRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all($token): Collection
    {
        $response = $this->repository->getAll($token);

        if ($response instanceof Response && $response->successful()) {
            return collect($response->json('data'))->mapInto(GudangResource::class);
        }

        return collect();
    }

    public function getOperators($token): Collection
    {
        $response = $this->repository->getOperators($token);

        if ($response instanceof Response && $response->successful()) {
            return collect($response->json('data'));
        }

        return collect();
    }

    public function count(){
        $token = session('token');
        $response = $this->repository->getAll($token);

        if ($response instanceof Response && $response->successful()) {
            return count(collect($response->json('data'))->mapInto(GudangResource::class));
        }

        return collect();
    }

    public function find($id, $token): ?GudangResource
    {
        $response = $this->repository->getById($id, $token);

        if ($response instanceof Response && $response->successful()) {
            return new GudangResource((object) $response->json('data'));
        }

        return null;
    }

    public function create(array $data, $token): array
    {
        $response = $this->repository->store($data, $token);
        return $this->parseResponse($response);
    }

    public function edit($id, array $data, $token): array
    {
        $response = $this->repository->update($id, $data, $token);
        return $this->parseResponse($response);
    }

    public function destroy($id, $token): array
    {
        $response = $this->repository->delete($id, $token);
        return $this->parseResponse($response);
    }

    protected function parseResponse($response): array
    {
        // Kalau response adalah array biasa (fallback)
        if (is_array($response)) {
            return [
                'success'  => $response['success'] ?? false,
                'message'  => $response['message'] ?? 'Terjadi kesalahan saat memproses data.',
                'errors'   => $response['errors'] ?? null,
                'data'     => $response['data'] ?? null,
                'restored' => $response['restored'] ?? false,
            ];
        }

        // Kalau response adalah objek Response dari Laravel
        if ($response instanceof Response) {
            if ($response->successful()) {
                return [
                    'success'  => true,
                    'message'  => $response->json('message') ?? 'Operasi berhasil',
                    'data'     => $response->json('data') ?? null,
                    'restored' => $response->json('restored') ?? false,
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Terjadi kesalahan saat memproses data.',
                'errors'  => $response->json('errors') ?? null,
            ];
        }

        // Unknown response type
        return [
            'success' => false,
            'message' => 'Terjadi kesalahan tak terduga.',
        ];
    }
}
