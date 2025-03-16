<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\MedicalTerm;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'name' => env('APP_NAME'),
            'url' => env('APP_URL')
        ]);
    }

    public function languages()
    {
        return response()->json(Language::all());
    }

    public function dictionary(Request $request)
    {
        // So‘rov parametrlari
        $letter = $request->query('letter', 'A'); // Default 'A'
        $sortOrder = $request->query('sortOrder', 'asc'); // 'asc' yoki 'desc'
        $limit = $request->query('limit', 6); // Nechta chiqishi kerak
        $page = $request->query('page', 1); // Sahifalash

        // Ma'lumotni so‘rash
        $query = MedicalTerm::whereHas('translations', function ($q) use ($letter) {
            $q->where('name', 'LIKE', "$letter%");
        })
            ->with(['translations' => function ($q) {
                $q->select('medical_term_id', 'language_id', 'name', 'description');
            }])
            ->orderBy('id', $sortOrder)
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $query->items(),
            'pagination' => [
                'total' => $query->total(),
                'per_page' => $query->perPage(),
                'current_page' => $query->currentPage(),
                'last_page' => $query->lastPage(),
            ]
        ]);
    }
}
