@php
    use Illuminate\Support\Facades\Storage;

    $data = $getState();

    if (is_callable($data)) {
        $data = $data();
    }

    $data = is_array($data) ? $data : [];

    $patient = $data['patient'] ?? null;

    $generalHealthFiles = $data['generalHealthFiles'] ?? [];
    $medicationFiles = $data['medicationFiles'] ?? [];
    $malariaFiles = $data['malariaFiles'] ?? [];
    $hivFiles = $data['hivFiles'] ?? [];
    $questionSummary = $data['question_summary'] ?? [];
    $symptomIds = $data['symptom_ids'] ?? [];
@endphp

@if (!$patient)
    <div class="text-sm text-gray-500">Select a patient to view summary.</div>
@else
    @php
        $profileUrl = !empty($patient['profile']) ? Storage::disk('public')->url($patient['profile']) : null;

        $dob = !empty($patient['dob']) ? \Carbon\Carbon::parse($patient['dob'])->format('d M Y') : '-';

        $groups = [
            'General Health Files' => $generalHealthFiles,
            'Medication Files' => $medicationFiles,
            'Malaria Files' => $malariaFiles,
            'HIV Files' => $hivFiles,
        ];

        // Resolve symptom names if IDs provided
        $symptomNames = [];
        if (!empty($symptomIds)) {
            $ids = is_array($symptomIds) ? $symptomIds : (json_decode($symptomIds, true) ?: []);
            $ids = array_filter(array_map('intval', $ids));
            if (!empty($ids)) {
                $symptomNames = \App\Models\Symptom::whereIn('id', $ids)->pluck('name')->toArray();
            }
        }
    @endphp

    <div class="rounded-xl bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800">
        <div class="p-4 sm:p-6">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        @if ($profileUrl)
                            <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="block">
                                <img src="{{ $profileUrl }}" alt="Profile"
                                    class="h-16 w-16 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-gray-800 sm:h-20 sm:w-20">
                            </a>
                        @else
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-50 text-xs text-gray-500 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700 sm:h-20 sm:w-20">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            Patient Summary
                        </div>

                        <div
                            class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                File: {{ $patient['filenumber'] ?? '-' }}
                            </span>
                            <span class="text-gray-300 dark:text-gray-700">|</span>
                            <span>Mobile: {{ $patient['mobile'] ?? '-' }}</span>
                            <span class="text-gray-300 dark:text-gray-700">|</span>
                            <span>{{ $patient['gender'] ?? '-' }}</span>
                            <span class="text-gray-300 dark:text-gray-700">|</span>
                            <span>DOB: {{ $dob }}</span>
                        </div>
                    </div>
                </div>

                <!-- Optional: quick action (keep if you want) -->
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Click attachments to open
                </div>
            </div>

            <!-- Divider -->
            <hr class="mb-4 border-t border-gray-200 dark:border-gray-800">

            <!-- Symptoms -->
            <div class="mt-4 mb-4">
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Symptoms</div>
                <div class="mt-2">
                    @if (empty($symptomNames))
                        <div class="text-sm text-gray-500">No symptoms selected</div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($symptomNames as $s)
                                <span
                                    class="inline-flex items-center rounded-md
           bg-gray-100 text-gray-800
           dark:bg-gray-700 dark:text-gray-100
           px-2 py-0.5 text-xs font-medium">
                                    {{ $s }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Divider -->
            <hr class="mb-4 border-t border-gray-200 dark:border-gray-800">

            <!-- Question Summary -->
            <div class="mt-4 mb-4">
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Question Summary</div>
                <div class="mt-3 space-y-3">
                    @if (empty($questionSummary))
                        <div class="text-sm text-gray-500">No question responses</div>
                    @else
                        @foreach ($questionSummary as $q)
                            <div
                                class="rounded-lg bg-white p-3 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800">
                                <div class="text-sm text-gray-700 dark:text-gray-100 mb-1">{!! $q['question_text'] ?? '-' !!}</div>
                                <div class="text-xs font-semibold text-gray-600 dark:text-gray-300">Answer:
                                    {{ $q['selected_answer'] ?? '-' }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- Notes -->
            <div class="mt-4 mb-4 grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-3">

                <div class="space-y-1">
                    <div class="text-xs font-semibold tracking-wide text-gray-500 dark:text-gray-400">
                        GENERAL HEALTH
                    </div>
                    <div class="whitespace-pre-line text-sm text-gray-900 dark:text-gray-100">
                        {{ $patient['generalhealth'] ?? '-' }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-xs font-semibold tracking-wide text-gray-500 dark:text-gray-400">
                        MEDICATION
                    </div>
                    <div class="whitespace-pre-line text-sm text-gray-900 dark:text-gray-100">
                        {{ $patient['medication'] ?? '-' }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-xs font-semibold tracking-wide text-gray-500 dark:text-gray-400">
                        ADDITIONAL COMMENT
                    </div>
                    <div class="whitespace-pre-line text-sm text-gray-900 dark:text-gray-100">
                        {{ $patient['additionalcomment'] ?? '-' }}
                    </div>
                </div>

            </div>


            <!-- Divider -->
            <hr class="mb-4 border-t border-gray-200 dark:border-gray-800">

            <!-- Attachments -->
            <div class="mt-4">
                <div class=" mb-4 items-center justify-between">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Attachments
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Opens in new tab
                    </div>
                </div>

                <div class="mt-4 mb-4 grid grid-cols-1 gap-x-4 gap-y-3 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($groups as $title => $files)
                        <div class="rounded-lg bg-gray-50 p-3 ring-1 ring-gray-200 dark:bg-gray-950 dark:ring-gray-800">
                            <div class="text-xs font-semibold tracking-wide text-gray-600 dark:text-gray-300">
                                {{ strtoupper($title) }}
                            </div>

                            @if (empty($files))
                                <div class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                                    No files
                                </div>
                            @else
                                <div class="mt-2 grid grid-cols-1 gap-x-2 gap-y-1.5 sm:grid-cols-2">
                                    @foreach ($files as $file)
                                        @php
                                            $url = Storage::disk('public')->url($file);
                                            $name = basename($file);
                                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, [
                                                'jpg',
                                                'jpeg',
                                                'png',
                                                'gif',
                                                'webp',
                                                'bmp',
                                                'svg',
                                            ]);
                                        @endphp

                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                            title="{{ $name }}"
                                            class="group relative aspect-square w-20 overflow-hidden rounded-md bg-white ring-1 ring-gray-200 hover:ring-gray-300 dark:bg-gray-900 dark:ring-gray-800">

                                            @if ($isImage)
                                                <img src="{{ $url }}" alt="{{ $name }}"
                                                    loading="lazy"
                                                    class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-110" />
                                            @else
                                                <div
                                                    class="flex h-full w-full flex-col items-center justify-center bg-gray-50 text-[10px] font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ strtoupper($ext ?: 'FILE') }}
                                                </div>
                                            @endif

                                            <div
                                                class="absolute inset-0 flex items-end bg-black/60 px-1 py-0.5 text-[10px] text-white opacity-0 transition group-hover:opacity-100">
                                                <span class="truncate">{{ $name }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>


            </div>

        </div>
    </div>
@endif
