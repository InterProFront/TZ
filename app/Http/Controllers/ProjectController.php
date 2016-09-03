<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Project;

class ProjectController extends BaseController
{
    /**
     * @return Response
     */
    public function getAllProjects()
    {
        $projects = Project::all();

        return view('allproject')->with('projects', $projects);
    }

    /**
     * @param string $projectname
     *
     * @return Response
     */
    public function getProject($projectname)
    {
        $project = Project::where('name', $projectname)->first();

        if(!$project)
        {
            throw new \Exception('Нет проекта с именем '.$projectname);
        }

        return view('project')->with('projects', $project);
    }

    public function create()
    {
        try
        {
            $project = new Project();
            $now = new \DateTime();
            $project->date = $now;
            $project->save();

            return ['status' => 'OK', 'id' => $project->id];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request_all = $request->all();

        $validator = Validator::make(
            $request_all,
            [
                'id' => 'required',
                'name' => 'required, unique:projects',
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try
        {
            $project = Project::find($request->input('id'));

            if(!$project)
            {
                throw new \Exception('Не найден проект с id '.$request->input('id'));
            }

            $project->name = $request->input('name');

            if ($request->has('title'))
            {
                $project->name = $request->input('title');
            }

            if ($request->has('description'))
            {
                $project->description = $request->input('description');
            }

            return ['status' => 'OK', 'id' => $project->id];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        try
        {
            $request_all = $request->all();

            $validator = Validator::make(
                $request_all,
                [
                    'id' => 'required'
                ]
            );

            if($validator->fails())
            {
                return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
            }

            $project = Project::find($request->input('id'));

            if(!$project)
            {
                throw new \Exception('Не найден проект с id '.$request->input('id'));
            }

            $project->delete();

            return ['status' => 'OK', 'id' => $request->input('id')];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }

    }

}

























