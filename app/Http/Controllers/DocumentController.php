<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Repositories\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    private $document;

    public function __construct()
    {
        $this->document = new Project(new Document);
    }

    public function store(Request $request)
    {
        foreach ($request->documents as $document) {
            $randomName = '_T' . dechex($request->task_id) . '_U' . dechex(auth()->user()->id) . '_N' . Str::uuid(); //. '.' . $document->document->getClientOriginalExtension();
            Storage::putFileAs('public\asset', $document, $randomName);
            Document::create([
                'original_name' => $document->getClientOriginalName(),
                'system_name' => $randomName,
                'user_id' => auth()->user()->id,
                'task_id' => $request->task_id
            ]);
        }
    }

    public function delete(Request $request)
    {
        Storage::delete("public/asset/" . $request->system_name);
        return $this->document->delete($request);
    }

    public function download(Request $request)
    {
//        $document = Documents::firstWhere('id', $request->id);
        $mime = mime_content_type(base_path("public/storage/asset/" . $request->system_name));
        $file = base_path("public/storage/asset/" . $request->system_name);
        return response()->download($file, $request->original_name, ['concat' . $mime]);
    }
}
