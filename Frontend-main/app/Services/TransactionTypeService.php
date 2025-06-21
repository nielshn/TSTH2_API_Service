<?php

namespace App\Services;

use App\Repositories\TransactionTypeRepository;
use Illuminate\Support\Collection;

class TransactionTypeService
{
    protected $repository;

    public function __construct(TransactionTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all($token): Collection
    {
        $response = $this->repository->getAll($token);


        if ($response->successful()) {
            return collect($response->json('data'));
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
    public function create($token, $data)
    {
        return $this->repository->store($token, $data);
    }

    public function update($token, $id, $data)
    {
        return $this->repository->update($token, $id, $data);
    }

    public function delete($token, $id)
    {
        return $this->repository->delete($token, $id);
    }
}
