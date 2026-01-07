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
     *   "selected_answer_text": "No, I am more forgetful than this."
     * }
     */
    public function nextQuestion(Request $request)
    {
        $validated = $request->validate([
            'current_question_id' => 'required|integer|exists:questions,id',
        ]);

        $currentQuestion = Question::findOrFail($validated['current_question_id']);

        // Now $currentQuestion->answers is already an array thanks to $casts
        $answers = $currentQuestion->answers;

        if (!is_array($answers)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid answers format.',
            ], 500);
        }

        // Find the selected answer (with trimming to be safe)
        $selectedAnswer = collect($answers)->first(function ($answer) use ($validated) {
            return trim($answer['answer']) === trim($validated['selected_answer_text']);
        });

        if (!$selectedAnswer) {
            return response()->json([
                'success' => false,
                'message' => 'Selected answer not found.',
                'available_answers' => collect($answers)->pluck('answer')->toArray(),
            ], 400);
        }

        $nextQuestionId = $selectedAnswer['next_question_id'];

        // Check if flow ends
        if ($nextQuestionId === false || $nextQuestionId === null || $nextQuestionId === 'false') {
            return response()->json([
                'success' => true,
                'message' => 'End of questionnaire for this symptom.',
                'data' => null,
                'is_end' => true,
            ]);
        }

        // Fetch next question
        $nextQuestion = Question::where('id', $nextQuestionId)
            ->where('is_active', 1)
            ->select('id', 'question_text', 'answers')
            ->first();

        if (!$nextQuestion) {
            return response()->json([
                'success' => false,
                'message' => 'Next question not found or inactive.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Next question retrieved',
            'data' => $nextQuestion,
            'is_end' => false,
        ]);
    }
}
