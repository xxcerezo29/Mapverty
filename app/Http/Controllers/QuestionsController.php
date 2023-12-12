<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index(){
        $columns = [
            '#',
            'Question',
            'Answer Type',
            'Actions'
        ];

        $data_display = [
            ['data' => 'DT_RowIndex'],
            ['data' => 'question'],
            ['data' => 'type-display'],
            ['data' => 'Actions' , 'orderable' => false, 'searchable' => false],
        ];

        $options = [
            '1' => 'Yes or No',
            '2' => 'Multiple Choice',
            '3' => 'Text',
            '4' => 'Number',
            '5' => 'Date',
        ];

        return view('questions', compact('columns', 'options', 'data_display'));
    }

    public function edit($id){
        $question = Question::find($id);
        $options = [
            '1' => 'Yes or No',
            '2' => 'Multiple Choice',
            '3' => 'Text',
            '4' => 'Number',
            '5' => 'Date',
        ];

        if($question->type == 2){
            $question->load('choices');
        }

        return view('pages.edit-question', compact('question', 'options'));
    }
}
