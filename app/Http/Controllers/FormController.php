<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Response;

class FormController extends Controller
{
    public function index(Request $request): Response
    {
        $forms = $request->user()->forms;

        return inertia('Forms/Index', [
            'forms' => $forms,
        ]);
    }

    public function create(): Response
    {
        return inertia('Forms/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $form = $request->user()->forms()->create(
            $request->validate([
                'name' => ['required', 'max:255', 'unique:forms'],
            ]),
        );

        return redirect()->route('forms.show', ['form' => $form]);
    }

    public function edit(Form $form): Response
    {
        return inertia('Forms/Edit', [
            'form' => $form,
        ]);
    }

    public function update(Request $request, Form $form)
    {
        //
    }

    public function destroy(Form $form)
    {
        //
    }
}
