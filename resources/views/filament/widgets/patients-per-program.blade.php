<x-filament::widget>
    <x-filament::card>
        <h2 class="mb-4 text-sm font-semibold text-gray-800">
            Patients per Program
        </h2>

        @if ($programs->isEmpty())
            <p class="py-6 text-center text-sm text-gray-500">
                No programs found
            </p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($programs as $program)
                    <li class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-700">
                            {{ $program->name }}
                        </span>

                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-100 text-xs font-semibold text-primary-700">
                            {{ $program->patients_count }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::card>
</x-filament::widget>
