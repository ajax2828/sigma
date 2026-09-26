<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'uptime' => '99.98%',
            'uptime_status' => 'Optimal',
            'visitors' => '125,721',
            'visitors_label' => 'Unique Platform Visitors (MTD)',
            'sessions' => '1.8M',
            'signups' => '1,502',
            'trial_starts' => '4,120',
            'conversion' => '83%',
            'cac' => '$45',
            'retention' => '78%',
            'social_engagement' => 65,
            'instagram_rate' => '81%',
        ];

        $milestones = [
            ['task' => 'Content strategy approval', 'team' => 'Team', 'date' => '16 Sep', 'completed' => false],
            ['task' => 'Final ad copy review', 'team' => 'Marketing', 'date' => '18 Sep', 'completed' => false],
            ['task' => 'Dev deploy', 'team' => 'Engineering', 'date' => '20 Sep', 'completed' => true],
        ];

        $cities = [
            ['name' => 'New York', 'users' => 71, 'top' => true],
            ['name' => 'London', 'users' => 21, 'top' => true],
            ['name' => 'Tokyo', 'users' => 19, 'top' => true],
            ['name' => 'Singapore', 'users' => 14, 'top' => true],
            ['name' => 'Sydney', 'users' => 8, 'top' => true],
        ];

        $posts = Post::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'milestones', 'cities', 'posts'));
    }
}
