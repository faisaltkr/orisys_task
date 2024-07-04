<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            return response()->json([
                'success' =>true,
                'data' => TaskResource::collection(Task::paginate(10)),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //in api route this route is not needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $task  = Task::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description
            ]);

            

            return response()->json([
                'success' =>true,
                'data' => new TaskResource($task),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {

        try {

            return response()->json([
                'success' => true,
                'data' => $task
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        try {

            return response()->json([
                'success' => true,
                'data' => $task
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        try {

            $task  = $task->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            return response()->json([
                'success' =>true,
                'data' => new TaskResource($task),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {

            if($task->delete())
            {
                return response()->json([
                    'success' => true,
                    'message' => "Task Deleted"
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
