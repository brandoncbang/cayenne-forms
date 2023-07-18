<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntryRequest;
use App\Mail\NewEntryEmail;
use App\Models\Entry;
use App\Models\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Inertia\Response as InertiaResponse;

class EntryController extends Controller
{
    /** @throws AuthorizationException */
    public function index(Request $request, Form $form): InertiaResponse
    {
        $this->authorize('viewAny', [Entry::class, $form]);

        $entries = $form
            ->entries()
            ->filter($request->query('filter'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return inertia('Entries/Index', [
            'form' => $form,
            'entries' => $entries,
        ]);
    }

    public function store(StoreEntryRequest $request, Form $form): RedirectResponse
    {
        abort_if($request->isJson(), Response::HTTP_UNSUPPORTED_MEDIA_TYPE);

        $entryIsSpam = ! is_null($form->honeypot_field) && $request->filled($form->honeypot_field);

        /** @var Entry $entry */
        $entry = $form->entries()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->all(),
            'deleted_at' => $entryIsSpam ? now() : null,
        ]);

        if (! $entry->trashed() && $form->sends_notifications) {
            Mail::to($form->user)->send(new NewEntryEmail($entry));
        }

        if (is_null($form->success_url)) {
            return redirect()->route('entries.success');
        }

        return redirect()->away($form->success_url);
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
