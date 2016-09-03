<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Commit;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AnswerController extends BaseController
{
    private function enumerate($id, $commit_id)
    {
        $query = Answer::query();
        $query->where('id', '=', $id);

        $number_query = Answer::selectRaw('MAX(number)+1 AS next_number, commit_id as c_id')->whereRaw('commit_id = '.$commit_id);

        $query->leftJoin(DB::raw('('.$number_query->toSql().') AS numb'), function($join)
        {
            $join->on('numb.c_id', '=', 'commit_id');
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
                'commit_id' => 'required'
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        $commit = Commit::find($request->input('commit_id'));

        if(!$commit)
        {
            throw new \Exception('Не найден коммент с id '.$request->input('commit_id'));
        }

        try
        {
            $answer = new Answer();

            $answer->commit_id = $commit->id;

            $now = new \DateTime();

            $answer->date = $now;

            $commit->save();

            $this->enumerate($answer->id, $commit->id);

            return ['status' => 'OK', 'id' => $answer->id];
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
                'text' => 'required'
            ]
        );

        if($validator->fails())
        {
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try
        {
            $answer = Answer::find($request->input('id'));

            if(!$answer)
            {
                throw new \Exception('Не найден ответ с id '.$request->input('id'));
            }

            $answer->text = $request->input('text');

            $answer->save();

            return ['status' => 'OK', 'id' => $answer->id];
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

            $answer = Answer::find($request->input('id'));

            if(!$answer)
            {
                throw new \Exception('Не найден ответ с id '.$request->input('id'));
            }

            $answer->delete();

            return ['status' => 'OK', 'id' => $request->input('id')];
        }
        catch(\Exception $exception)
        {
            return ['status'=>'error', 'error'=>$exception->getMessage()];
        }
    }


}
