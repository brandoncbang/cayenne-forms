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
    public function index(Request $request, Form $form): InertiaResponse
    {
        $this->authorize('viewAny', [Entry::class, $form]);

        $entries = $form
            ->entries()
            ->when($request->query('filter') === 'archived', fn ($query) => $query->onlyArchived())
            ->when($request->query('filter') === 'trashed', fn ($query) => $query->onlyTrashed())
            ->orderByDesc('id')
            ->paginate(20)
            ->onEachSide(2);

        return inertia('Entries/Index', [
            'form' => $form,
            'entries' => $entries,
        ]);
    }

    public function store(Request $request, Form $form): RedirectResponse
    {
        abort_if($request->isJson(), Response::HTTP_UNSUPPORTED_MEDIA_TYPE);

        $form->entries()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->all(),
        ]);

        if (is_null($form->success_url)) {
            return redirect()->route('entries.success');
        }

        return redirect()->away($form->success_url);
    }

    /** @throws AuthorizationException */
    public function show(Entry $entry): JsonResponse
    {
        $this->authorize('view', $entry);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /** @throws AuthorizationException */
    public function update(Request $request, Entry $entry): RedirectResponse
    {
        $this->authorize('update', $entry);

        $entry->update(
            $request->validate([
                'archived_at' => ['nullable', 'date'],
                'deleted_at' => ['nullable', 'date'],
            ]),
        );

        return redirect()->back();
    }

    /** @throws AuthorizationException */
    public function destroy(Entry $entry): RedirectResponse
    {
        $this->authorize('delete', $entry);

        $entry->forceDelete();

        return redirect()->back();
    }
}
