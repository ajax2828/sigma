<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
   public function show()
    {
        $registrants = Registration::select('id', 'name', 'study', 'Institute', 'email', 'phone', 'qr_code_path', 'is_scanned')
            ->paginate(12);
        $registrantsCount = Registration::count();
        $attendedCount = Registration::where('is_scanned', true)->count();
        $notAttendedCount = $registrantsCount - $attendedCount;

        return view('admin', [
            'registrantsCount' => $registrantsCount,
            'attendedCount' => $attendedCount,
            'notAttendedCount' => $notAttendedCount,
            'registrants' => $registrants,
        ]);
    }

    public function getDashboardData(Request $request)
    {
        $page = $request->query('page', 1);

        $query = Registration::select('id', 'name', 'study', 'Institute', 'email', 'phone', 'qr_code_path', 'is_scanned', 'scanned_at');

        Log::info('Dashboard Data Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'page' => $page,
            'total' => $query->count() // Tambahkan total untuk debugging
        ]);

        $registrants = $query->paginate(12, ['*'], 'page', $page);

        $registrantsCount = Registration::count();
        $attendedCount = Registration::where('is_scanned', true)->count();
        $notAttendedCount = $registrantsCount - $attendedCount;

        return response()->json([
            'registrantsCount' => $registrantsCount,
            'attendedCount' => $attendedCount,
            'notAttendedCount' => $notAttendedCount,
            'registrants' => $registrants->items(),
            'currentPage' => $registrants->currentPage(),
            'lastPage' => $registrants->lastPage(),
            'lastChecked' => now()->toDateTimeString(), // Tetap kirim untuk kompatibilitas, meskipun tidak digunakan
        ]);
    }

    public function searchRegistrants(Request $request)
    {
        $query = $request->input('name', '');
        $page = $request->query('page', 1);

        $searchQuery = Registration::select('id', 'name', 'study', 'Institute', 'email', 'phone', 'qr_code_path', 'is_scanned')
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', '%' . $query . '%');
            });

        Log::info('Search Registrants Query', [
            'sql' => $searchQuery->toSql(),
            'bindings' => $searchQuery->getBindings(),
            'query' => $query,
            'page' => $page,
            'total' => $searchQuery->count() // Tambahkan total untuk debugging
        ]);

        $registrants = $searchQuery->paginate(12, ['*'], 'page', $page);

        return response()->json([
            'registrants' => $registrants->items(),
            'currentPage' => $registrants->currentPage(),
            'lastPage' => $registrants->lastPage(),
        ]);
    }
}