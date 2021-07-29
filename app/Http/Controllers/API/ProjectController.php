<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Files;
use App\Models\User;
use App\Models\Category;
use App\Models\Icon;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
   
    public function index()
    {
        $project = Project::with('icon', 'review')->paginate(20);
        return $project;
    }

    public function show($id)
    {
        
        $project = Project::find($id);
        
        if ($project == null) {
            return response()->json([
                'success' => false,
                'message' => 'Project Not Exist'
            ]);
        }
        
        $project->user;
        $project->category;
        $project->ss;
        $project->file;
        $project->icon;
        $project->type;

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if ($project == null) {
            return response()->json([
                'success' => false,
                'message' => 'Project Not Exist'
            ]);
        }
        if ($request->name != null) {
            $project->name = $request->name;
        }
        if ($request->desc != null) {
            $project->desc = $request->desc;
        }
        if ($request->editor != null) {
            $project->editors = $request->editor;
        }
        if ($request->update != null) {
            $project->update = $request->update;
        }
        if ($request->category_id != null) {
            $project->category_id = $request->category_id;
        }
        if ($request->open != null) {
            $open = $request->open;
            $project->open = $open;
        }
        $project->updated_at = date('Y-m-d H:i:s');
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project Edited'
        ]);
    }

    public function destroy($id)
    {   
        $project = Project::find($id);
        if ($project == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Exists'
            ]);
        }

        $userId = $project->user_id;
        $user = Auth::user();
        if ($user->id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You Not Author'
            ]);
        }

        
        $path = $project->file->path;
        if($path == null){
            return response()->json([
                'success' => false,
                'message' => 'Data Not Exists'
            ]);
        }
        $isExists = Storage::exists($path);

        if(!$isExists){
            return response()->json([
                'success' => false,
                'message' => 'File Not Exists'
            ]);
        }

        Storage::delete($project->icon->path);
        Storage::delete($project->file->path);
        $screen = $project->ss()->get();
        foreach ($screen as $ss) {
            Storage::delete($ss->path);
        }
        $project->file()->delete();
        $project->ss()->delete();
        $project->review()->delete();
        $project->icon()->delete();
        
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project Deleted'
        ]);
    }

    public function store(Request $request) 
    { 
        $request->validate([
            'file' => 'required',
        ]);
        //File SWB
        $project = Project::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'open' => $request->open,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $file = $request->file('file');
        $sizeFile = $file->getSize()/1048576 ; //get in MB
        $fileName = now()->timestamp."_".$file->getClientOriginalName();
        $pathFile = 'public/projects';
        $resultFile = $request->file('file')->storeAs($pathFile, $fileName);

        $id = $project->id;

        $FILE = Files::create([
            'project_id' => $id,
            'name' => $file->getClientOriginalName(),
            'path' => $resultFile,
            'name_file' => $fileName,
            'size' => $sizeFile,
        ]);

        //ICon
        $icon = $request->file('icon');
        $iconName = now()->timestamp."_".$icon->getClientOriginalName();
        $pathIcon = 'public/icon';
        $resultIcon = $request->file('icon')->storeAs($pathIcon, $iconName);

        $ICON = Icon::create([
            'project_id' => $id,
            'path' => $resultIcon,
            'name_file' => $iconName,
        ]);

        //Screenshots
        if ($request->hasfile('images')) {
            $file = $request->file('images');
            $images = [] ;
            foreach ($file as $fi) {
                $imagesName = now()->timestamp."_".$fi->getClientOriginalName();
                $images[] = $imagesName;
                $pathImages = 'public/ss';
                $pathSS = $fi->storeAs($pathImages, $imagesName);
                Screenshot::create([
                    'project_id' => $id,
                    'path' => $pathSS,
                    'name_file' => $imagesName,
                ]);
                }
        }
        

        return response()->json([
            'success' => true,
            'message' => 'File Uploaded',
            'file' => $FILE,
            'icon' => $ICON,
            'ss' => $images,
        ]);
        
    }

    public function download($file)
    {   
        $project = Project::where('name', $file)->first();
        $downloads = $project->downloads;
        $project->downloads = $downloads + 1;
        $project->save();
        dd($project->downloads);
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
    
    public function myProject($myId)
    {
        $project = Project::all()->where('user_id', '=', $myId);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }
    
    public static function popular()
    {
        $project = Project::orderBy('downloads', 'desc')->with('icon','review')->get();

        return $project;
    }

    public static function editors()
    {
        $project = Project::where('editors', '=', 1)->with('icon','review')->get();

        return $project;
    }

    public function getData()
    {
        $editors = ProjectController::editors()->take(20);
        $popular = ProjectController::popular()->take(20);

        return \response()->json([
            'editors' => $editors,
            'popular' => $popular,
        ]);
    }

    
    public static function newProjects()
    {
        $projects = Project::orderBy('updated_at', 'desc')->with('icon','review')->get();

        return $projects->paginate(20);
    }

}
