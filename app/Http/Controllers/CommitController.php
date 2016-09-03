<?php

namespace App\Http\Controllers;

use App\Commit;
use App\Page;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Project;

class CommitController extends BaseController
{
    private function enumerate($id, $page_id)
    {
        $query = Commit::query();
        $query->where('id', '=', $id);

        $number_query = Commit::selectRaw('MAX(number)+1 AS next_number, page_id as p_id')->whereRaw('page_id = '.$page_id);

        $query->leftJoin(DB::raw('('.$number_query->toSql().') AS numb'), function($join)
        {
            $join->on('numb.p_id', '=', 'page_id');
        });

        $query->update(['number' => DB::raw('numb.next_number')]);
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
                'page_id' => 'required',
                'x' => 'required',
                'y' => 'required'
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        $page = Page::find($request->input('page_id'));

        if(!$page)
        {
            throw new \Exception('Не найдена страница с id '.$request->input('page_id'));
        }

        try
        {
            $commit = new Commit();

            $commit->page_id = $page->id;

            $now = new \DateTime();

            $commit->date = $now;

            $commit->x = $request->input('x');
            $commit->y = $request->input('y');

            $commit->save();

            $this->enumerate($commit->id, $page->id);

            return ['status' => 'OK', 'id' => $commit->id];
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
                'title' => 'required'
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try
        {
            $commit = Commit::find($request->input('id'));

            if(!$commit)
            {
                throw new \Exception('Не найден коммент с id '.$request->input('id'));
            }

            $commit->title = $request->input('title');

            if ($request->has('text'))
            {
                $commit->text = $request->input('text');
            }

            if ($request->has('complete'))
            {
                $commit->complete = $request->input('complete');
            }

            $commit->save();

            return ['status' => 'OK', 'id' => $commit->id];
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

            $commit = Commit::find($request->input('id'));

            if(!$commit)
            {
                throw new \Exception('Не найден коммент с id '.$request->input('id'));
            }

            $commit->delete();

            return ['status' => 'OK', 'id' => $request->input('id')];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }
    }


}
