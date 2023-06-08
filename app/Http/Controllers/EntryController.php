<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

class EntryController extends Controller
{
    /** @throws AuthorizationException */
    public function index(Form $form): InertiaResponse
    {
        $this->authorize('viewAny', [Entry::class, $form]);

        $entries = $form->entries()->latest()->paginate(8)->onEachSide(2);

        return inertia('Entries/Index', [
            'form' => $form,
            'entries' => $entries,
        ]);
    }

    public function store(Request $request, Form $form): JsonResponse|RedirectResponse
    {
        $form->entries()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->all(),
        ]);

        if ($request->isJson()) {
            return response()->json([
                'message' => 'Your form submission has been received.'
            ], Response::HTTP_CREATED);
        }

        if (is_null($form->success_url)) {
            return redirect()->route('entries.success');
        }

        return redirect()->away($form->success_url);
    }

    public function show(Entry $entry)
    {
        //
    }

    public function update(Request $request, Entry $entry)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
