<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites;
        $events = [];
        foreach ($favorites as $favorite) {
            if ($favorite->favorite) $events[] = $favorite->event;
        }

        return view('favorites.index', [
            'events' => $events
        ]);
    }

    public function update($id)
    {
        $favorite = Favorite::find($id);

        if ($favorite->favorite) {
            $favorite->favorite = false;
            $message = 'Successfully unfavored';
        }
        else {
            $favorite->favorite = true;
            $message = 'Successfully favorite';
        }
        $favorite->save();

        return redirect()
            ->back()
            ->with('favorite-success', $message);
    }

    public function delete()
    {

    }
}
