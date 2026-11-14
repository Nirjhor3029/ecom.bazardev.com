<?php

namespace App\Modules\Orders\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardService $dashboard): JsonResponse
    {
        $this->authorize('orders.order.view');

        $range = $dashboard->resolveRange($request->string('range', '7d')->toString(), $request->input('from'), $request->input('to'));

        return response()->json(['data' => $dashboard->build($range)]);
    }

    public function export(Request $request, DashboardService $dashboard): StreamedResponse
    {
        $this->authorize('orders.order.view');

        $range = $dashboard->resolveRange($request->string('range', '7d')->toString(), $request->input('from'), $request->input('to'));
        $name = 'orders-'.$range['from']->format('Ymd').'-'.$range['to']->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($dashboard, $range) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel reads Bengali names correctly
            foreach ($dashboard->exportRows($range) as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
