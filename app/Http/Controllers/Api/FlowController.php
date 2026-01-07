<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Symptom;
use Illuminate\Http\Request;

class FlowController extends Controller
{
    /**
     * Get all active symptoms (id, name)
     * GET /api/v1/flow/symptoms
     */
    public function symptoms()
    {
        $symptoms = Symptom::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Symptoms retrieved successfully',
            'data' => $symptoms,
        ]);
    }

    /**
     * Get the first question for a selected symptom
     * GET /api/v1/flow/questions/first/{symptomId}
     */
    public function firstQuestion($symptomId)
    {
        Symptom::findOrFail($symptomId);

        $question = Question::where('symptomid', $symptomId)
            ->where('question_index', '1')
            ->where('is_active', 1)
            ->select('id', 'question_text', 'answers')
            ->first();

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'No starting question found for this symptom.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'First question retrieved',
            'data' => $question,
            'is_end' => false,
        ]);
    }

    /**
     * Get next question based on selected answer
     * POST /api/v1/flow/questions/next
     * Body: {
     *   "current_question_id": 2,
     * }
     */
   public function getQuestionById(Request $request)
{
    $validated = $request->validate([
        'question_id' => 'required|integer|exists:questions,id',
    ]);

    $question = Question::query()
        ->where('id', $validated['question_id'])
        ->select('id', 'question_text', 'answers')
        ->first();

    return response()->json([
        'success' => true,
        'message' => 'Question retrieved successfully.',
        'data' => $question,
    ]);
}


}
