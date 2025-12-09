<head>
    <title>LetsQuiz — Resultaten</title>
</head>
@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Quiz Resultaten</h1>

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Gebruiker</th>
                <th class="border px-4 py-2">Categorie</th>
                <th class="border px-4 py-2">Correct</th>
                <th class="border px-4 py-2">Fout</th>
                <th class="border px-4 py-2">Tijd (s)</th>
                <th class="border px-4 py-2">Gems</th>
                <th class="border px-4 py-2">Datum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td class="border px-4 py-2">{{ $result->user->name }}</td>
                    <td class="border px-4 py-2">{{ $result->category->title ?? $result->category->folder_guid }}</td>
                    <td class="border px-4 py-2">{{ $result->correct_answers }}</td>
                    <td class="border px-4 py-2">{{ $result->wrong_answers }}</td>
                    <td class="border px-4 py-2">{{ $result->time_taken }}</td>
                    <td class="border px-4 py-2">{{ $result->gems_earned }}</td>
                    <td class="border px-4 py-2">{{ $result->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
