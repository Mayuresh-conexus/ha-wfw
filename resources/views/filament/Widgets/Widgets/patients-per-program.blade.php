<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">
            Patients per Program
        </h2>

        <ul class="space-y-2">
            @foreach ($programs as $program)
                <li class="flex justify-between">
                    <span>{{ $program->name }}</span>
                    <span class="font-semibold">
                        {{ $program->patients_count }}
                    </span>
                </li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament::widget>
