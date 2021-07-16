<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    public function getReviews($id)
    {
        $reviews = Review::all()->where('project_id', '=', $id);
        $lenght = \count($reviews);
        $stars = 0;
        for ($i=0; $i < $lenght; $i++) { 
            $stars = $stars + $reviews[$i]->stars;
        }

        return \response()->json([
            'success' => true,
            'data' => $reviews,
            'stars' => $stars,
        ]);
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
        Review::create([
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
            'review' => $request->review,
            'stars' => $request->stars,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thanks for review'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        $review->review = $request->review;
        $review->stars = $request->stars;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review Edited'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review has been deleted'
        ]);
    }
}
