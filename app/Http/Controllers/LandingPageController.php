<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\LandingContent;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // Get all landing contents
        $contents = LandingContent::all()->keyBy('key');

        $members = Member::orderBy('sort_order')->orderBy('id')->get()->map(fn (Member $member) => [
            'initial' => $member->initial ?? '',
            'name' => $member->name,
            'role' => $member->role ?? '',
            'code' => $member->code ?? '',
            'motto' => $member->motto ?? '',
            'desc' => $member->description ?? '',
            'photo' => $member->photo,
        ])->values()->all();

        $achievements = Achievement::orderBy('sort_order')->orderBy('id')->get()->map(fn (Achievement $achievement) => [
            'icon' => $achievement->icon ?? '',
            'image' => $achievement->image,
            'title' => $achievement->title,
            'year' => $achievement->year ?? '',
            'desc' => $achievement->description ?? '',
        ])->values()->all();

        // Kabar terbaru: semua post published, ditampilkan sebagai rail horizontal di header.
        $posts = Post::with('user')->where('status', 'published')->latest()->get();

        return view('public.landing', [
            'contents' => $contents,
            'members' => $members,
            'achievements' => $achievements,
            'posts' => $posts,
        ]);
    }
}
