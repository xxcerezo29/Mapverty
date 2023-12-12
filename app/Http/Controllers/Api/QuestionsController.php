<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{
    public function getQuestions(){
        $questions = Question::all();

        return datatables()->of($questions)
            ->addIndexColumn()
            ->addColumn('type-display', function($row){
                $type = '';
                switch ($row->type){
                    case 1:
                        $type = 'Yes or No';
                        break;
                    case 2:
                        $type = 'Multiple Choice';
                        break;
                    case 3:
                        $type = 'Text';
                        break;
                    case 4:
                        $type = 'Number';
                        break;
                    case 5:
                        $type = 'Date';
                        break;
                }
                return $type;
            })
            ->addColumn('Actions', function($row){
                $btn = '<div data-id="'.$row->id.'"> <a href="/questions/'.$row->id.'" class="edit btn btn-primary btn-sm">Edit</a> <button onclick="remove('.$row->id.')" class="delete btn btn-danger btn-sm">Delete</button></div>';
                return $btn;
            })->rawColumns(['Actions'])
            ->toJson();
    }

    public function store(Request $request){
        $request->validate([
            'question' => 'required',
            'type' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $question = Question::create([
                'question' => $request->question,
                'type' => $request->type
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Question saved successfully.', 'data' => $question, 'title' => 'Success']);
    }

    public function edit($id, Request $request){
        $validated = $request->validate([
            'question' => 'required',
            'type' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $question = Question::find($id);

            if($validated['type'] != 2 && $question->type == 2){
                $question->choices()->delete();
            }

            $question->update([
                'question' => $validated['question'],
                'type' => $validated['type']
            ]);

            DB::commit();

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Question saved successfully.', 'data' => $question, 'title' => 'Success']);
    }

    public function delete($id){

        DB::beginTransaction();

        try {
            $question = Question::find($id);
            $question->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Question deleted successfully.', 'data' => $question, 'title' => 'Success']);
    }
}
