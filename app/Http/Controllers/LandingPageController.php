<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\LandingContent;
use App\Models\Member;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get all landing contents
        $contents = LandingContent::all()->keyBy('key');
        
        $members = Member::orderBy('sort_order')->orderBy('id')->get()->map(fn (Member $member) => [
            'initial' => $member->initial ?? '',
            'name' => $member->name,
            'role' => $member->role ?? '',
            'code' => $member->code ?? '',
            'desc' => $member->description ?? '',
            'photo' => $member->photo,
        ])->values()->all();

        $achievements = Achievement::orderBy('sort_order')->orderBy('id')->get()->map(fn (Achievement $achievement) => [
            'icon' => $achievement->icon ?? '',
            'title' => $achievement->title,
            'year' => $achievement->year ?? '',
            'desc' => $achievement->description ?? '',
        ])->values()->all();

        return view('public.landing', [
            'contents' => $contents,
            'members' => $members,
            'achievements' => $achievements,
        ]);
    }
}