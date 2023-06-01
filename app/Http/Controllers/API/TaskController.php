<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Models\Hobby;
use App\Models\Task;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::with('hobbies','state','city')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        DB::beginTransaction();

        try {
            $task = new Task();
            $task->name = $request->input('name');
            $task->email = $request->input('email');
            $task->contact_number = $request->input('contact_number');
            $task->gender = $request->input('gender');
            $task->state_id = $request->input('state');
            $task->city_id = $request->input('city');
            $task->save();
            $hobbies = $request->input('hobbies');
            $originalImage = $request->file('profile_pic');
            if ($originalImage) {
                $imageName = $originalImage->getClientOriginalName();
                $imagePath = $originalImage->storeAs('public', $imageName);
                $task->profile_pic = $imageName;
            }
            $task->save();
            foreach($hobbies as $hobby)
            {
                Hobby::create([
                    'task_id' => $task->id,
                    'name' => $hobby
                ]);
            }
            DB::commit();

            return response()->json(['message' => 'Task created successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to create task'], 500);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $task = Task::find($id);
            $task->name = $request->input('name');
            $task->email = $request->input('email');
            $task->contact_number = $request->input('contact_number');
            $task->gender = $request->input('gender');
            $task->state_id = $request->input('state');
            $task->city_id = $request->input('city');
            $task->save();
            $hobbies = $request->input('hobbies');
            if ($request->hasFile('profile_pic')) {
                $originalImage = $request->file('profile_pic');
                if ($originalImage) {
                    $imageName = $originalImage->getClientOriginalName();
                    $imagePath = $originalImage->storeAs('public', $imageName);                    
                }
            }   
            $task->profile_pic = ((isset($imageName))?$imageName:$task->profile_pic);       
            $existingHobbies = $task->hobbies()->pluck('name')->toArray();
        
            // Delete removed hobbies
            $removedHobbies = array_diff($existingHobbies, $hobbies);
            Hobby::whereIn('name', $removedHobbies)->delete();
            
            // Add new hobbies
            $newHobbies = array_diff($hobbies, $existingHobbies);
            foreach ($newHobbies as $hobby) {
                Hobby::create([
                    'task_id' => $task->id,
                    'name' => $hobby
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Task updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->hobbies()->delete();
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
