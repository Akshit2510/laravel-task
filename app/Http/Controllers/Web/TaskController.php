<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->hobbies = ['Cricket', 'Music', 'Reading', 'Painting', 'Cooking', 'Photography'];
        $this->state = State::all();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hobbies = $this->hobbies;
        $states = $this->state;
        return view('task.create',compact('hobbies','states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('hobbies','state','city')->where('id',$id)->first();
        $hobbies = $this->hobbies;
        $states = $this->state;
        return view('task.show',compact('hobbies','states','task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::with('hobbies','state','city')->where('id',$id)->first();
        $hobbies = $this->hobbies;
        $states = $this->state;
        return view('task.edit',compact('hobbies','states','task'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd("sdgfg");
    }

    public function getCities(Request $request,$stateId)
    {
        return City::where('state_id',$stateId)->get();
    }
}
