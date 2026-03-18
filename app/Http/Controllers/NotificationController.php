<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function dismissAlert(Request $request)
    {
        $id = (int) $request->input('id');
        if ($id) {
            DB::table('system_alerts')->where('id', $id)->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolved_by' => auth()->id(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
