<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request) 
    { 
        $request->validate([
            'file' => 'required',
        ]);
        
        $file = $request->file('file');
        $fileName = now()->timestamp."_".$file->getClientOriginalName();
        $path = 'public/projects';

        $result = $request->file('file')->storeAs($path, $fileName);

        return response()->json([
            'success' => true,
            'message' => 'File Uploaded',
            'result' => $result
        ]);
        
    }

    public function download($file)
    {   
        $path = 'storage/projects/'.$file;
        return response()->download(public_path($path), $file);
    }

    public function request($id)
    {
        $project = Project::find($id);
        if ($project == null) {
            return response()->json([
                'success' => false,
                'message' => 'Project Not Exists On Server'
            ]); 
        }
        $file = $project->name_file;
        //Cek if exist
        $path = 'public/projects/'.$file;
        $isExists = Storage::exists($path);

        if(!$isExists){
            return response()->json([
                'success' => false,
                'message' => 'File Not Exists'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $file
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'name_file' => 'required',
            'desc' => 'required',
            'path' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
        ]);

        Project::create([
            'name' => $validatedData['name'],
            'name_file' => $validatedData['name_file'],
            'path' => $validatedData['path'],
            'desc' => $validatedData['desc'],
            'update' => $request->update,
            'user_id' => $validatedData['user_id'],
            'category_id' => $validatedData['category_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File Uploaded'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        \dd($request->name);
        $project->name = $request->name;
        $project->desc = $request->desc;
        $project->update = $request->update;
        $project->category_id = $request->category_id;
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project Edited'
        ]);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project Deleted'
        ]);
    }
    
}
