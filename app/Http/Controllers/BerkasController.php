<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Http\Resources\WithoutDataResource;
use App\Models\Berkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BerkasController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'filename' => 'required',
            'file' => 'required|file',
            'kategori' => 'required'
        ],[
            'filename.required' => 'Filename harus diisi',
            'file.required' => 'File harus diisi',
            'file.file' => 'File harus berupa file',
            'kategori.required' => 'Kategori harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(new WithoutDataResource(Response::HTTP_NOT_ACCEPTABLE, $validator->messages()),Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $file = $request->file('file');
            // $filename = $file->hashName();
            $filename = $request->filename;
            $size = $this->formatBytes($file->getSize());
            $file->move(public_path('storage/file'), $filename);

            $berkas = Berkas::create([
                'filename' => $filename,
                'path' => Storage::url('file/' . $filename),
                'type' => mime_content_type(public_path('storage/file/'.$filename)),
                'size' => $size,
                'kategori' => $request->kategori,
                'user_id' => Auth::user()->id
            ]);
        } catch (\Exception $e) {
            return response()->json(new WithoutDataResource(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage()));
        }

        return response()->json(new DataResource(Response::HTTP_OK, 'File berhasil diupload', [
            'nama_file' => $filename,
            'path' => Storage::url('file/' . $filename),
            'kategori' => $request->kategori,
            'ext' => mime_content_type(public_path('storage/file/'.$filename)),
            'size' => $size,
            'id_file' => $berkas
        ]),Response::HTTP_OK);
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function getfile(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_file' => 'required'
        ],[
            'id_file.required' => 'Id file harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(new WithoutDataResource(Response::HTTP_NOT_ACCEPTABLE, $validator->messages()),Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $berkas = Berkas::where('id', $request->id_file)->first();
        } catch (\Exception $e) {
            return response()->json(new WithoutDataResource(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something Wrong'));
        }

        return response()->download(public_path('/storage/file/'. $berkas->filename));
    }
}