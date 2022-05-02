<?php

namespace App\Http\Controllers;

use App\Mail\requestInvitation;
use App\Mail\sendInvitation;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function requestForInvitation($event_id)
    {
        $event = Event::find($event_id);

        $owner = $event->user;
        $email = $owner->email;
        //Sends out an email to the event owner

        Mail::to($email)->send(new requestInvitation(Auth::user(), $event));

        return redirect()
            ->back()
            ->with('success', "You asked event owner for invitation");
    }

    //Show the form for an event owner to invite someone else
    public function inviteForm()
    {
        //find all events that user can invite

        $events = Event::all()
            ->where('is_public', '=', false)
            ->where('user_id', '=', Auth::user()->id);

        $users = User::all()
            ->where('id', '!=', Auth::user()->id);

        return view('invitations.create', [
            'users' => $users,
            'events' => $events
        ]);
    }

    //process the form submission and send the invite by email
    public function sendOutInvitation(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'event' => 'required'
        ]);
        $event_id = $request->input('event');
        $user_id = $request->input('user');

        $event = Event::find($event_id);
        $user = User::find($user_id);

        $this->authorize('update', $event);

        //check invitation exists
        if (!Invitation::where([
            'event_id' => $event_id,
            'user_id' => $user_id
        ])->exists()) {
            $invitation = new Invitation();
            $invitation->user()->associate($user);
            $invitation->event()->associate($event);
            $invitation->save();
        }

        Mail::to($user->email)->send(new sendInvitation($user, $event));

        return redirect()
            ->back()
            ->with('success', "You invited {$user->name}.");
    }
}
