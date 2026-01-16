<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    /**
     * Get all active symptoms with their related questions
     * Perfect format for mobile SQLite storage
     */
    public function getSymptomsWithQuestions()
    {
        $symptoms = Symptom::where('is_active', 1)
            ->with(['questions' => function ($query) {
                $query->where('is_active', 1)
                    ->select(
                        'id',
                        'symptomid',
                        'question_text',
                        'gender',           // gender filter if exists
                        'question_index',
                        'answers'           // JSON string of answers
                    )
                    ->orderBy('question_index');
            }])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'body_section_id',
                'tag',
                'iscritical'
            ]);

        // Clean & optimized format for mobile
        $formatted = $symptoms->map(function ($symptom) {
            return [
                'symptom_id'     => $symptom->id,
                'symptom_name'   => $symptom->name,
                'body_section'   => $symptom->body_section_id,
                'tag'            => $symptom->tag,
                'is_critical'    => (bool) $symptom->iscritical,
                'questions' => $symptom->questions->map(function ($question) {
                    return [
                        'question_id'    => $question->id,
                        'question_text'  => strip_tags($question->question_text),
                        'gender_filter'  => $question->gender ?? null,
                        'order_index'    => $question->question_index,
                        'answers'        => is_array($question->answers)
                            ? $question->answers
                            : (json_decode($question->answers, true) ?? []),
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Symptoms and questions loaded successfully',
            'data'    => $formatted,
            'total_symptoms' => $formatted->count(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
