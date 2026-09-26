@extends('admin.layouts.app')

@section('title', 'System Overview - SIGMA Admin')

@section('content')
    <h1 class="page-title">System Overview</h1>
    <p class="page-subtitle">Real-time insights and performance metrics</p>

    <!-- Top Row: Stats -->
    <div class="dashboard-grid">
        <!-- Service Uptime -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Service Uptime Last 30 Days</div>
            <div class="progress-ring">
                <svg width="100" height="100" viewBox="0 0 100 100">
                    <circle class="bg" cx="50" cy="50" r="42"/>
                    <circle class="progress" cx="50" cy="50" r="42" stroke-dasharray="264" stroke-dashoffset="264" data-progress="99.98"/>
                </svg>
                <div class="value">{{ $stats['uptime'] }}</div>
            </div>
            <div style="text-align:center;"><span class="stat-tag optimal">{{ $stats['uptime_status'] }}</span></div>
        </div>

        <!-- Unique Visitors -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-icon cyan">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="stat-value">{{ $stats['visitors'] }}</div>
            <div class="stat-label" style="margin-top:0.5rem;">{{ $stats['visitors_label'] }}</div>
        </div>

        <!-- Acquisition Funnel -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="funnel-row">
                <div class="funnel-icon cyan">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div class="funnel-text">Sessions</div>
                <div class="funnel-value">{{ $stats['sessions'] }}</div>
            </div>
            <div class="funnel-row">
                <div class="funnel-icon green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div class="funnel-text">New Signups</div>
                <div class="funnel-value">{{ $stats['signups'] }}</div>
            </div>
        </div>

        <!-- Funnel Metrics -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="funnel-row">
                <div class="funnel-text">Trial Starts</div>
                <div class="funnel-value">{{ $stats['trial_starts'] }}</div>
            </div>
            <div class="funnel-row">
                <div class="funnel-text">Conversion</div>
                <div class="funnel-value">{{ $stats['conversion'] }}</div>
                <span class="funnel-arrow up">▲</span>
            </div>
            <div class="funnel-row">
                <div class="funnel-text">CAC</div>
                <div class="funnel-value">{{ $stats['cac'] }}</div>
                <span class="funnel-arrow down">▼</span>
            </div>
            <div class="funnel-row">
                <div class="funnel-text">Retention</div>
                <div class="funnel-value">{{ $stats['retention'] }}</div>
                <span class="funnel-arrow up">▲</span>
            </div>
        </div>
    </div>

    <!-- Middle Row: Chart + Line Chart -->
    <div class="dashboard-grid-2">
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Performance Trends</div>
            <div class="chart-container">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Traffic Overview</div>
            <div class="chart-container">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Milestones + Social + Map -->
    <div class="dashboard-grid-3">
        <!-- Milestones -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Campaign Milestones & Due Dates</div>
            @foreach ($milestones as $milestone)
                <div class="milestone">
                    <div class="milestone-check {{ $milestone['completed'] ? 'done' : '' }}"></div>
                    <div>
                        <div class="milestone-text {{ $milestone['completed'] ? 'done' : '' }}">{{ $milestone['task'] }}</div>
                        <div class="milestone-meta">{{ $milestone['team'] }}, {{ $milestone['date'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Social Media -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Social Media Engagement</div>
            <div class="gauge-container">
                <div class="gauge">
                    <svg width="120" height="60" viewBox="0 0 120 60">
                        <path class="gauge-bg" d="M 10 55 A 50 50 0 0 1 110 55"/>
                        <path class="gauge-fill" d="M 10 55 A 50 50 0 0 1 110 55" stroke-dasharray="157" stroke-dashoffset="{{ 157 - (157 * $stats['social_engagement'] / 100) }}"/>
                    </svg>
                    <div class="gauge-value">{{ $stats['social_engagement'] }}%</div>
                </div>
                <div class="gauge-legend">
                    <div class="gauge-legend-item"><div class="gauge-legend-dot cyan"></div> Instagram: {{ $stats['instagram_rate'] }}</div>
                    <div class="gauge-legend-item"><div class="gauge-legend-dot green"></div> Engagement: Strong</div>
                </div>
            </div>
        </div>

        <!-- Global User Distribution -->
        <div class="card">
            <span class="card-close">✕</span>
            <div class="stat-label">Global User Distribution (Top 5)</div>
            <div class="map-container">
                <svg viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255,255,255,0.05)" d="M150,120 Q200,100 250,110 Q300,90 350,100 Q400,80 450,90 Q500,70 550,80 Q600,60 650,70 Q700,50 750,60 L750,350 L50,350 L50,130 Q100,140 150,120 Z"/>
                    <ellipse cx="200" cy="150" rx="80" ry="40" fill="rgba(255,255,255,0.03)"/>
                    <ellipse cx="500" cy="130" rx="100" ry="50" fill="rgba(255,255,255,0.03)"/>
                    <ellipse cx="650" cy="180" rx="60" ry="30" fill="rgba(255,255,255,0.03)"/>
                </svg>
                @foreach ($cities as $index => $city)
                    @php
                        $positions = [
                            ['left' => '25%', 'top' => '35%'],
                            ['left' => '48%', 'top' => '28%'],
                            ['left' => '72%', 'top' => '38%'],
                            ['left' => '68%', 'top' => '58%'],
                            ['left' => '78%', 'top' => '72%'],
                        ];
                    @endphp
                    <div class="map-pin {{ $city['top'] ? 'top' : '' }}" style="left:{{ $positions[$index]['left'] }};top:{{ $positions[$index]['top'] }};" data-city="{{ $city['name'] }}" data-users="{{ $city['users'] }}"></div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Performance Chart
        const perfCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(perfCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Revenue',
                    data: [65, 72, 68, 82, 78, 85, 82],
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#38bdf8'
                }, {
                    label: 'Users',
                    data: [45, 52, 48, 65, 62, 70, 65],
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74, 222, 128, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#4ade80'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } },
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } }
                }
            }
        });

        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Visits',
                    data: [42, 45, 38, 52, 48, 35, 28],
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#38bdf8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } },
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } }
                }
            }
        });

        // Animate progress ring
        document.querySelectorAll('.progress-ring .progress').forEach(el => {
            const progress = el.dataset.progress;
            const offset = 264 - (264 * progress / 100);
            setTimeout(() => { el.style.strokeDashoffset = offset; }, 300);
        });
    </script>
@endsection
