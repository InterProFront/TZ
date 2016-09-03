<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Project;

class PageController extends BaseController
{
    /**
     * @param string $projectname
     * @param string $pagename
     *
     * @return Response
     */
    public function getPage($projectname, $pagename)
    {
        $project = Project::where('name', $projectname)->first();

        if(!$project)
        {
            throw new \Exception('Нет проекта с именем '.$projectname);
        }

        $page = Page::where('name', $pagename)->where('project_id', $project->id)->first();

        if(!$page)
        {
            throw new \Exception('Нет страницы с именем '.$pagename.' в проекте '.$projectname);
        }

        return view('page')->with('page', $page);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $request_all = $request->all();

        $validator = Validator::make(
            $request_all,
            [
                'id' => 'required',
                'project_id' => 'required',
                'name' => 'required, unique:pages',
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        $project = Project::find($request->input('project_id'));

        if(!$project)
        {
            throw new \Exception('Не найден проект с id '.$request->input('id'));
        }

        try
        {
            $page = new Page();
            $page->project = $project->id;
            $now = new \DateTime();
            $page->date = $now;
            $page->save();

            return ['status' => 'OK', 'id' => $page->id];
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
                'name' => 'required, unique:pages',
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try
        {
            $page = Page::find($request->input('id'));

            if(!$page)
            {
                throw new \Exception('Не найдена страница с id '.$request->input('id'));
            }

            $page->name = $request->input('name');

            if ($request->has('title'))
            {
                $page->name = $request->input('title');
            }

            if ($request->has('description'))
            {
                $page->description = $request->input('description');
            }

            $page->save();

            return ['status' => 'OK', 'id' => $page->id];
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

            $page = Page::find($request->input('id'));

            if(!$page)
            {
                throw new \Exception('Не найдена страница с id '.$request->input('id'));
            }

            $page->delete();

            return ['status' => 'OK', 'id' => $request->input('id')];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }
    }


}
