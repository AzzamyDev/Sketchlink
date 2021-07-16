<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;

class SScontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Screenshot::create([
            'name' => $request->name,
            'name_file' => $request->name_file,
            'path' => $request->path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Screenshot Uploaded'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Screenshot  $screenshot
     * @return \Illuminate\Http\Response
     */
    public function show(Screenshot $screenshot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Screenshot  $screenshot
     * @return \Illuminate\Http\Response
     */
    public function edit(Screenshot $screenshot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Screenshot  $screenshot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Screenshot $screenshot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Screenshot  $screenshot
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//ID Project
    {
       
        $screenshot = Screenshot::where('project_id', $id);
        if (count($screenshot->get()) == 0) {
            return \response()->json([
                'success' => false
            ]);
        }

        $screenshot->delete();
        return \response()->json([
            'success' => true,
            'message' => 'Delete Successfully'
        ]);
    }
}
