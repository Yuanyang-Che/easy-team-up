<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Favorite;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        //get all invoices
        $this->authorize('viewAny', Event::class);

        $events = Event::with(['user'])
            ->select('events.*')
            ->leftJoin('users', 'events.user_id', '=', 'users.id')
            ->orderBy('events.id', 'ASC')
            ->get();


        return view('events.index', [
            'events' => $events
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:40',
            'description' => 'required',
            'location' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);
//        $title = $request->input('title');
//        $description = $request->input('description');

        //"2022-04-26 12:00 to 2022-05-03 12:00"
        //$datetime = $request->input('datetime');

        $event = new Event();

        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->datetime = $request->input('datetime');

        $event->lat = $request->input('lat');
        $event->lng = $request->input('lng');
        $event->user()->associate(Auth::user());
        $event->is_public = $request->input('is_public') == 'on';
        $event->save();

        return redirect()->route('event.index');
        //dd($datetime);
    }

    private function favoriteCreateIfNone($event_id, $user_id)
    {
        if (!Favorite::where([
            'event_id' => $event_id,
            'user_id' => $user_id
        ])->exists()) {
            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->event_id = $event_id;
            $favorite->favorite = false;
            $favorite->save();
        }
        $favorite = Favorite::where([
            'event_id' => $event_id,
            'user_id' => $user_id
        ])->first();

        return $favorite;
    }

    public function show($id)
    {
        $event = Event::find($id);
        $comments = $event->comments;

        $this->authorize('view', $event);

        $favorite = $this->favoriteCreateIfNone($id, Auth::user()->id);

        return view('events.show', [
            'event' => $event,
            'comments' => $comments,
            'favorite' => $favorite,
        ]);

    }

    public function edit($id)
    {
        $event = Event::find($id);
        $comments = $event->comments;

        $this->authorize('update', $event);

        $favorite = $this->favoriteCreateIfNone($id, Auth::user()->id);

        return view('events.edit', [
            'event' => $event,
            'comments' => $comments,
            'favorite' => $favorite,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required | max:40',
            'description' => 'required',
            'location' => 'required',
            'lat' => 'required | numeric',
            'lng' => 'required | numeric',
        ]);

        $event = Event::find($id);

        $this->authorize('update', $event);

        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->datetime = $request->input('datetime');

        $event->lat = $request->input('lat');
        $event->lng = $request->input('lng');
        $event->user()->associate(Auth::user());
        $event->is_public = $request->input('is_public') == 'on';
        $event->save();

        return redirect()
            ->route('event.index')
            ->with('success', 'Successfully updated event');
    }

    public function delete($id)
    {
        //find all the comments, favorites and invitations that event and delete them
        $comments = Comment::all()
            ->where('event_id', ' = ', $id);
        foreach ($comments as $comment) {
            $comment->delete();
        }

        $favorites = Favorite::all()
            ->where('event_id', ' = ', $id);
        foreach ($favorites as $favorite) {
            $favorite->delete();
        }

        $invitations = Invitation::all()
            ->where('event_id', ' = ', $id);
        foreach ($invitations as $invitation) {
            $invitation->delete();
        }

        $event = Event::find($id);

        $this->authorize('delete', $event);

        $event->delete();

        return redirect()
            ->route('event . index')
            ->with('success', 'Successfully deleted event');
    }
}
