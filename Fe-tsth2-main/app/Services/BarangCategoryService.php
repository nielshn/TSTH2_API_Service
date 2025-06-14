<?php

namespace App\Services;

use App\Repositories\BarangCategoryRepository;
use App\Http\Resources\BarangCategoryResource;
use Illuminate\Support\Collection;

class BarangCategoryService
{
    protected $repository;

    public function __construct(BarangCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all($token): Collection
    {
        $response = $this->repository->getAll($token);

        if ($response->successful()) {
            return collect($response->json('data'))
                ->mapInto(BarangCategoryResource::class);
        }

        return collect();
    }

    public function count()
    {
        $token = session('token');
        $response = $this->repository->getAll($token);

        if ($response->successful()) {
            return count($response->json('data') ?? []);
        }

        return 0;
    }

    public function find($id, $token): ?BarangCategoryResource
    {
        $response = $this->repository->getById($id, $token);

        if ($response->successful()) {
            return new BarangCategoryResource((object)$response->json('data'));
        }

        return null;
    }

    public function create(array $data, $token)
    {
        return $this->repository->store($data, $token);
    }

    public function edit($id, array $data, $token)
    {
        return $this->repository->update($id, $data, $token);
    }

    public function destroy($id, $token)
    {
        return $this->repository->delete($id, $token);
    }
}
