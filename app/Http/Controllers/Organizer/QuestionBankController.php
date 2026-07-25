<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, QuestionBank, Question, Option};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionBankController extends Controller
{
    public function index(Event $event)
    {
        $banks = $event->questionBanks()->withCount('questions')->get();
        return view('organizer.questions.banks', compact('event', 'banks'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $event->questionBanks()->create($validated);

        return redirect()->route('organizer.questions.index', $event)
            ->with('success', 'Bank soal berhasil dibuat!');
    }

    public function show(QuestionBank $bank)
    {
        $bank->load('questions.options');
        return view('organizer.questions.show', compact('bank'));
    }

    public function storeQuestion(Request $request, QuestionBank $bank)
    {
        $validated = $request->validate([
            'type' => 'required|in:multiple_choice,essay',
            'content' => 'required|string',
            'explanation' => 'nullable|string',
            'score' => 'required|numeric|min:0',
            'negative_score' => 'nullable|numeric|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
            'category' => 'nullable|string|max:100',
            'options' => 'required_if:type,multiple_choice|array|min:2|max:6',
            'options.*.content' => 'required_if:type,multiple_choice|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $bank) {
            $question = $bank->questions()->create([
                'type' => $validated['type'],
                'content' => $validated['content'],
                'explanation' => $validated['explanation'] ?? null,
                'score' => $validated['score'],
                'negative_score' => $validated['negative_score'] ?? 0,
                'difficulty' => $validated['difficulty'],
                'category' => $validated['category'] ?? null,
            ]);

            if ($validated['type'] === 'multiple_choice' && isset($validated['options'])) {
                foreach ($validated['options'] as $i => $opt) {
                    $question->options()->create([
                        'content' => $opt['content'],
                        'is_correct' => $opt['is_correct'] ?? false,
                        'order_index' => $i,
                    ]);
                }
            }
        });

        return redirect()->route('organizer.questions.bank.show', $bank)
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    public function importQuestion(Request $request, QuestionBank $bank)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new \App\Imports\QuestionImport($bank->id);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            return back()->with('success', "Berhasil mengimpor " . $import->successCount . " soal ke dalam bank soal.");
        } catch (\Exception $e) {
            return back()->withErrors(['import' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    public function destroyQuestion(Question $question)
    {
        $bank = $question->bank;
        $question->delete();

        return redirect()->route('organizer.questions.bank.show', $bank)
            ->with('success', 'Soal berhasil dihapus.');
    }
}
