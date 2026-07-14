<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDocuments = Document::count();

        $totalCategories = Category::count();

        $totalUsers = User::count();

        $activeDocuments = Document::where('status', 'Aktif')->count();

        return view('dashboard', [
    'totalDocuments' => Document::count(),
    'totalCategories' => Category::count(),
    'totalUsers' => User::count(),
    'activeDocuments' => Document::where('status', 'Aktif')->count(),
]);
    }
}