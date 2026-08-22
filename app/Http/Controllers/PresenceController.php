<?php

namespace App\Http\Controllers;

use App\Services\PresenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    protected PresenceService $presenceService;

    public function __construct(PresenceService $presenceService)
    {
        $this->presenceService = $presenceService;
    }

    /**
     * Handle incoming presence heartbeat from authenticated browser tabs.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $connectionId = $request->input('connection_id');
        if (!$connectionId || !is_string($connectionId) || strlen($connectionId) > 64) {
            return response()->json(['success' => false, 'message' => 'Invalid connection ID.'], 422);
        }

        $metadata = [
            'has_interaction' => (bool) $request->input('has_interaction', false),
            'device_type'     => $request->input('device_type', 'desktop'),
            'browser'         => $request->input('browser'),
            'platform'        => $request->input('platform'),
            'ip_address'      => $request->ip(),
            'user_agent'      => $request->userAgent(),
            'session_id'      => session()->getId(),
        ];

        $result = $this->presenceService->recordHeartbeat($user, $connectionId, $metadata);

        return response()->json($result);
    }

    /**
     * Handle explicit disconnection when tab/window is closing.
     */
    public function disconnect(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $connectionId = $request->input('connection_id');
        if ($connectionId && is_string($connectionId)) {
            $this->presenceService->recordDisconnect($user, $connectionId);
        }

        return response()->json(['success' => true]);
    }
}
