<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Response as InertiaResponse;

class FormController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $forms = $request
            ->user()
            ->forms()
            ->with(['latest_entry'])
            ->orderBy('id', 'desc')
            ->paginate(8)
            ->onEachSide(2);

        return inertia('Forms/Index', [
            'forms' => $forms,
        ]);
    }

    public function create(): InertiaResponse
    {
        return inertia('Forms/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $form = $request->user()->forms()->create(
            $request->validate([
                'name' => [
                    'required', 'max:255',
                    Rule::unique('forms')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id)),
                ],
                'success_url' => ['nullable', 'max:255', 'url'],
                'sends_notifications' => ['required', 'boolean'],
                'honeypot_field' => ['nullable', 'max:255'],
            ]),
        );

        return redirect()->route('forms.edit', ['form' => $form]);
    }

    /** @throws AuthorizationException */
    public function edit(Form $form): InertiaResponse
    {
        $this->authorize('update', $form);

        return inertia('Forms/Edit', [
            'form' => $form,
        ]);
    }

    /** @throws AuthorizationException */
    public function update(Request $request, Form $form): RedirectResponse
    {
        $this->authorize('update', $form);

        $form->update(
            $request->validate([
                'name' => [
                    'required', 'max:255',
                    Rule::unique('forms')
                        ->where(fn (Builder $query) => $query->where('user_id', $request->user()->id))
                        ->ignore($form),
                ],
                'success_url' => ['nullable', 'max:255', 'url'],
                'sends_notifications' => ['required', 'boolean'],
                'honeypot_field' => ['nullable', 'max:255'],
            ]),
        );

        return redirect()->route('forms.entries.index', ['form' => $form]);
    }

    /** @throws AuthorizationException */
    public function destroy(Form $form): RedirectResponse
    {
        $this->authorize('delete', $form);

        $form->delete();

        return redirect()->route('forms.index');
    }
}
