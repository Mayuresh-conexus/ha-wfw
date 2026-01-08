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
    @endphp

    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
            <div class="md:col-span-1">
                @if ($profileUrl)
                    <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $profileUrl }}" alt="Profile" class="h-24 w-24 rounded-lg object-cover">
                    </a>
                @else
                    <div
                        class="flex h-24 w-24 items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        No Image
                    </div>
                @endif
            </div>

            <div class="md:col-span-5">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <div class="text-xs font-semibold text-gray-500">File Number</div>
                        <div class="text-sm">{{ $patient['filenumber'] ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-gray-500">Mobile</div>
                        <div class="text-sm">{{ $patient['mobile'] ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-gray-500">Gender</div>
                        <div class="text-sm">{{ $patient['gender'] ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-gray-500">DOB</div>
                        <div class="text-sm">{{ $dob }}</div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-xs font-semibold text-gray-500">General Health</div>
                    <div class="text-sm whitespace-pre-line">{{ $patient['generalhealth'] ?? '-' }}</div>
                </div>

                <div class="mt-4">
                    <div class="text-xs font-semibold text-gray-500">Medication</div>
                    <div class="text-sm whitespace-pre-line">{{ $patient['medication'] ?? '-' }}</div>
                </div>

                <div class="mt-4">
                    <div class="text-xs font-semibold text-gray-500">Additional Comment</div>
                    <div class="text-sm whitespace-pre-line">{{ $patient['additionalcomment'] ?? '-' }}</div>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="text-xs font-semibold text-gray-500">Attachments</div>

                    @foreach ($groups as $title => $files)
                        <div>
                            <div class="text-xs font-semibold text-gray-500">{{ $title }}</div>

                            @if (empty($files))
                                <div class="text-sm">No files</div>
                            @else
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                    @foreach ($files as $file)
                                        @php $url = Storage::disk('public')->url($file); @endphp
                                        <li>
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                                class="text-primary-600 hover:underline">
                                                {{ basename($file) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endif
