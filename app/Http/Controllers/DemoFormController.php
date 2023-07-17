<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Inertia\Response;

class DemoFormController extends Controller
{
    public function show(): Response
    {
        $form = Form::firstWhere('name', 'Demo Form');

        return inertia('Demo/Form', [
            'form' => $form,
        ]);
    }

    public function success(): Response
    {
        $form = Form::firstWhere('name', 'Demo Form');
        $entry = $form->entries()->latest()->first();

        return inertia('Demo/Success', [
            'form' => $form,
            'entry' => $entry,
        ]);
    }
}
