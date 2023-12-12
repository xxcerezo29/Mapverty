<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChoicesController extends Controller
{
    public function getChoices($id){
        $choices = Choice::where('question_id', $id)->get();
        return response()->json($choices);
    }

    public function getChoice($id){
        $choice = Choice::find($id);
        return response()->json($choice);
    }
    public function createChoice(Request $request, $id){
        $validated = $request->validate([
            'choice' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $choice = Choice::create([
                'choice' => $validated['choice'],
                'question_id' => $id
            ]);

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Choice saved successfully.', 'data' => $choice, 'title' => 'Success']);
    }

    public function editChoice(Request $request, $id, $choiceId){
        $validated = $request->validate([
            'choice' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $choice = Choice::find($choiceId);
            $choice->update([
                'choice' => $validated['choice'],
            ]);

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Choice updated successfully.', 'data' => $choice, 'title' => 'Success']);
    }

    public function removeChoice($id, $choiceId){
        DB::beginTransaction();
        try {
            $choice = Choice::find($choiceId);
            $choice->delete();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Choice deleted successfully.', 'title' => 'Success']);
    }
}
