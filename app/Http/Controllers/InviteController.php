<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Response;

class InviteController extends Controller
{
    public function show(Invite $invite)
    {
        abort_if($invite->hasBeenUsed(), Response::HTTP_NOT_FOUND);

        return inertia('Invites/Show', [
            'invite' => $invite->load('user'),
        ]);
    }
}
