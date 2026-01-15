<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionBulkUploadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'symptoms' => ['required', 'array', 'min:1'],
            'symptoms.*.symptomid' => ['required', 'string', 'max:100'],
            'symptoms.*.gender' => ['nullable', 'in:Male,Female,Other,All'],
            'symptoms.*.default_is_active' => ['nullable', 'in:0,1'],
            'symptoms.*.questions' => ['required', 'array', 'min:1'],
            'symptoms.*.questions.*.question_index' => ['required', 'string', 'max:255'],
            'symptoms.*.questions.*.question_text' => ['required', 'string'],
            'symptoms.*.questions.*.is_active' => ['nullable', 'in:0,1'],
            'symptoms.*.questions.*.answers' => ['required', 'array', 'min:1'],
            'symptoms.*.questions.*.answers.*.answer' => ['required', 'string', 'max:255'],
            'symptoms.*.questions.*.answers.*.next_question_id' => ['nullable', 'string', 'max:255'],
        ]);

        $allResults = [];
        $errors = [];

        DB::transaction(function () use ($data, &$allResults, &$errors) {

            foreach ($data['symptoms'] as $symptomData) {

                $symptomId = (string) $symptomData['symptomid'];
                $gender = $symptomData['gender'] ?? null;
                $defaultIsActive = $symptomData['default_is_active'] ?? 1;

                $incomingQuestions = $symptomData['questions'] ?? [];

                // Check for duplicate question_index within this symptom
                $indexes = array_map(fn($q) => $q['question_index'], $incomingQuestions);
                $duplicateIndexes = $this->findDuplicates($indexes);
                if (!empty($duplicateIndexes)) {
                    $errors[] = [
                        'symptomid' => $symptomId,
                        'message' => 'Duplicate question_index values found in this symptom',
                        'duplicates' => $duplicateIndexes
                    ];
                    continue;
                }

                $indexToId = [];

                // Create all questions first
                foreach ($incomingQuestions as $q) {
                    $question = new Question();
                    $question->symptomid          = $symptomId;
                    $question->gender             = $gender;
                    $question->question_index     = $q['question_index'];
                    $question->question_text      = $q['question_text'];
                    $question->is_active          = $q['is_active'] ?? $defaultIsActive;
                    $question->answers            = []; // temporary
                    if ($question->isFillable('created_by')) {
                        $question->created_by = $request->user()->id ?? null;
                    }
                    $question->save();

                    $indexToId[$q['question_index']] = $question->id;
                }

                // Update answers (store next_question_id as string, no resolution)
                foreach ($incomingQuestions as $q) {
                    $qid = $indexToId[$q['question_index']];

                    $answersToStore = [];
                    foreach ($q['answers'] ?? [] as $a) {
                        $next = $a['next_question_id'] ?? null;

                        $answersToStore[] = [
                            'answer'             => $a['answer'],
                            'next_question_id' => $next,  // ← store as string (no ID lookup)
                        ];
                    }

                    Question::whereKey($qid)->update(['answers' => $answersToStore]);
                }

                $allResults[] = [
                    'symptomid' => $symptomId,
                    'questions_created' => count($incomingQuestions),
                    'index_to_id' => $indexToId
                ];
            }
        });

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Some symptoms failed validation',
                'errors' => $errors,
                'successful' => $allResults
            ], 422);
        }

        return response()->json([
            'message' => 'Bulk upload of multiple symptoms completed',
            'count' => count($allResults),
            'results' => $allResults
        ], 201);
    }

    private function findDuplicates(array $values): array
    {
        $counts = array_count_values($values);
        return array_keys(array_filter($counts, fn ($c) => $c > 1));
    }
}