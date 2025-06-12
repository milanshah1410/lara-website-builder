<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectFile;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index($projectId)
    {
        return ProjectFile::where('project_id', $projectId)->get();
    }

    public function update(Request $request, ProjectFile $file)
    {
        $file->update(['content' => $request->input('content')]);
        return response()->json(['message' => 'Updated']);
    }
}
