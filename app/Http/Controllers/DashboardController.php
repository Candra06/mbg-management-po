<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inspection;
use App\Models\Invoice;
use App\Models\PoItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPo = Menu::count();
        $totalPoBiaya = PoItem::sum('total_harga');
        $totalInspection = Inspection::count();
        $totalInvoice = Invoice::count();
        $totalNominalInvoice = Invoice::sum('total_tagihan');

        $latestMenus = Menu::with(['poItems', 'inspection', 'invoice'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $recentInspections = Inspection::with(['menu', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.dashboard.index', compact(
            'totalPo',
            'totalPoBiaya',
            'totalInspection',
            'totalInvoice',
            'totalNominalInvoice',
            'latestMenus',
            'recentInspections'
        ));
    }
}
