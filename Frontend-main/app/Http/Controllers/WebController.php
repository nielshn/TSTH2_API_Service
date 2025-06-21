<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebResource;
use App\Services\WebService;
use Illuminate\Http\Request;

class WebController extends Controller
{
    protected $webService;

    public function __construct(WebService $webService)
    {
        $this->webService = $webService;
    }

    public function index(Request $request)
    {

        $token = session('token');
        $response = $this->webService->getById($token, 1);

        $web = (array) ($response->data ?? $response);

        return view('frontend.web.index', ['web' => $web, 'webId' => 1]);
    }

    public function update(Request $request, $id)
    {
        $token = session('token');

        // Ambil hanya field yang kita butuhkan (nama, deskripsi, dan base64 dari JS)
        $data = $request->only(['web_nama', 'web_deskripsi', 'web_logo']);

        // Pastikan web_logo itu string base64, bukan UploadedFile
        if ($request->filled('web_logo')) {
            $base64 = $data['web_logo'];
            if (!strpos($base64, ',')) {
                return back()->withErrors('Format base64 tidak valid');
            }
            // tidak perlu decode di frontend; frontend service akan menulis ke backend_api
        } else {
            // jika tidak ada perubahan logo, jangan kirim field ini ke API
            unset($data['web_logo']);
        }

        // Panggil service untuk update via API
        $this->webService->update($token, $id, $data);

        return redirect()
            ->route('webs.index')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
