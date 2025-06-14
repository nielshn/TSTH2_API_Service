<?php

namespace App\Services;

use App\Repositories\SatuanRepository;

class SatuanService
{
    protected $repository;

    public function __construct(SatuanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all($token)
    {
        return $this->repository->getAll($token);
    }
    public function satuancount()
    {
        $token = session('token');
        $data = $this->repository->getAll($token);

        if (is_array($data)) {
            return count($data);
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
