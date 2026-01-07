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
            'symptomid' => ['required'],
            'gender' => ['nullable', 'in:Male,Female,Other,All'],
            'default_is_active' => ['nullable', 'in:0,1'],

            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_index' => ['required', 'string', 'max:255'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.is_active' => ['nullable', 'in:0,1'],

            'questions.*.answers' => ['required', 'array', 'min:1'],
            'questions.*.answers.*.answer' => ['required', 'string', 'max:255'],
            'questions.*.answers.*.next_question_index' => ['nullable', 'string', 'max:255'],
        ]);

        $symptomId = (string) $data['symptomid'];
        $gender = $data['gender'] ?? null;

        $defaultIsActive = array_key_exists('default_is_active', $data)
            ? (int) $data['default_is_active']
            : 1;

        $incomingQuestions = $data['questions'];

        $indexes = array_map(fn ($q) => $q['question_index'], $incomingQuestions);
        $duplicateIndexes = $this->findDuplicates($indexes);
        if (!empty($duplicateIndexes)) {
            return response()->json([
                'message' => 'Duplicate question_index values in payload.',
                'duplicates' => $duplicateIndexes,
            ], 422);
        }

        $payloadIndexSet = array_flip($indexes);

        foreach ($incomingQuestions as $q) {
            foreach ($q['answers'] as $a) {
                $next = $a['next_question_index'] ?? null;

                if ($next === null || $next === '' || strtoupper($next) === 'END') {
                    continue;
                }

                if (!isset($payloadIndexSet[$next])) {
                    return response()->json([
                        'message' => 'Invalid next_question_index. It must be END or match a question_index in this payload.',
                        'invalid_next_question_index' => $next,
                        'question_index' => $q['question_index'],
                    ], 422);
                }
            }
        }

        $result = DB::transaction(function () use ($incomingQuestions, $symptomId, $gender, $defaultIsActive, $request) {
            $indexToId = [];

            foreach ($incomingQuestions as $q) {
                $question = new Question();
                $question->symptomid = $symptomId;
                $question->gender = $gender; // store gender on each question
                $question->question_index = $q['question_index'];
                $question->question_text = $q['question_text'];
                $question->is_active = array_key_exists('is_active', $q)
                    ? (int) $q['is_active']
                    : $defaultIsActive;

                // IMPORTANT: answers column is NOT NULL in your DB
                $question->answers = [];

                if ($question->isFillable('created_by')) {
                    $question->created_by = $request->user()->id;
                }

                $question->save();
                $indexToId[$question->question_index] = $question->id;
            }

            $updated = 0;

            foreach ($incomingQuestions as $q) {
                $qid = $indexToId[$q['question_index']];

                $answersToStore = [];
                foreach ($q['answers'] as $a) {
                    $next = $a['next_question_index'] ?? null;

                    $nextId = false;
                    if ($next !== null && $next !== '' && strtoupper($next) !== 'END') {
                        $nextId = (int) $indexToId[$next];
                    }

                    $answersToStore[] = [
                        'answer' => $a['answer'],
                        'next_question_id' => $nextId,
                    ];
                }

                Question::query()->whereKey($qid)->update([
                    'answers' => $answersToStore,
                ]);

                $updated++;
            }

            return [
                'questions_created' => count($incomingQuestions),
                'questions_updated_with_answers' => $updated,
                'index_to_id' => $indexToId,
            ];
        });

        return response()->json([
            'message' => 'Bulk upload successful.',
            'result' => $result,
        ], 201);
    }

    private function findDuplicates(array $values): array
    {
        $counts = array_count_values($values);
        return array_keys(array_filter($counts, fn ($c) => $c > 1));
    }
}
