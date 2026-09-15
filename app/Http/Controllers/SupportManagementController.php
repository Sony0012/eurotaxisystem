<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportManagementController extends Controller
{
    /**
     * Display the Messenger-style support interface.
     */
    public function index(Request $request)
    {
        $selectedDriverId = $request->get('driver_id');

        // Fetch all drivers with their latest message info and online status
        $drivers = User::where('role', 'driver')
            ->select('users.*')
            ->addSelect([
                'latest_message' => SupportMessage::select('message')
                    ->whereColumn('driver_id', 'users.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
                'latest_message_time' => SupportMessage::select('created_at')
                    ->whereColumn('driver_id', 'users.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
                'unread_count' => SupportMessage::select(DB::raw('count(*)'))
                    ->whereColumn('driver_id', 'users.id')
                    ->where('sender_type', 'driver')
                    ->where('is_read', false)
            ])
            ->orderByRaw('latest_message_time IS NULL, latest_message_time DESC')
            ->orderBy('full_name')
            ->get();

        $chatMessages = [];
        $selectedDriver = null;
        $isDriverOnline = false;
        $driverLastSeen = null;

        if ($selectedDriverId) {
            $selectedDriver = User::where('role', 'driver')->findOrFail($selectedDriverId);
            
            $isDriverOnline = (bool) $selectedDriver->is_online;
            if (!$isDriverOnline && $selectedDriver->last_seen_at) {
                $seenAt = \Carbon\Carbon::parse($selectedDriver->last_seen_at);
                if ($seenAt->diffInMinutes(now()) <= 5) {
                    $isDriverOnline = true;
                } else {
                    $driverLastSeen = $seenAt->diffForHumans();
                }
            }

            // Mark driver messages as read
            SupportMessage::where('driver_id', $selectedDriverId)
                ->where('sender_type', 'driver')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $chatMessages = SupportMessage::where('driver_id', $selectedDriverId)
                ->where('hidden_by_admin', false)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('support.index', compact('drivers', 'selectedDriver', 'chatMessages', 'isDriverOnline', 'driverLastSeen'));
    }

    /**
     * Get chat messages for the selected driver (AJAX).
     */
    public function getMessagesJson($driverId)
    {
        // Mark driver messages as read
        SupportMessage::where('driver_id', $driverId)
            ->where('sender_type', 'driver')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $driver = User::find($driverId);
        $isOnline = false;
        $lastSeen = null;
        if ($driver) {
            $isOnline = (bool) $driver->is_online;
            if (!$isOnline && $driver->last_seen_at) {
                $seenAt = \Carbon\Carbon::parse($driver->last_seen_at);
                if ($seenAt->diffInMinutes(now()) <= 5) {
                    $isOnline = true;
                } else {
                    $lastSeen = $seenAt->diffForHumans();
                }
            }
        }

        $messages = SupportMessage::where('driver_id', $driverId)
            ->where('hidden_by_admin', false)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'driver_id' => $msg->driver_id,
                    'sender_type' => $msg->sender_type,
                    'sender_id' => $msg->sender_id,
                    'message' => $msg->message,
                    'attachment' => $msg->attachment,
                    'is_read' => (bool) $msg->is_read,
                    'created_at' => $msg->created_at ? $msg->created_at->toISOString() : null,
                    'time' => $msg->created_at ? $msg->created_at->format('h:i A') : '',
                    'human_time' => $msg->created_at ? $msg->created_at->diffForHumans() : '',
                ];
            });

        return response()->json([
            'success' => true,
            'driver_is_online' => $isOnline,
            'driver_last_seen' => $lastSeen,
            'messages' => $messages
        ]);
    }

    /**
     * Get unread counts, latest messages, and online status for the driver list (AJAX).
     */
    public function getStatusJson()
    {
        $drivers = User::where('role', 'driver')
            ->select('id', 'first_name', 'last_name', 'full_name', 'is_online', 'last_seen_at')
            ->addSelect([
                'latest_message' => SupportMessage::select('message')
                    ->whereColumn('driver_id', 'users.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
                'latest_message_time' => SupportMessage::select('created_at')
                    ->whereColumn('driver_id', 'users.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
                'unread_count' => SupportMessage::select(DB::raw('count(*)'))
                    ->whereColumn('driver_id', 'users.id')
                    ->where('sender_type', 'driver')
                    ->where('is_read', false)
            ])
            ->get()
            ->map(function ($d) {
                $isOnline = (bool) $d->is_online;
                if (!$isOnline && $d->last_seen_at && \Carbon\Carbon::parse($d->last_seen_at)->diffInMinutes(now()) <= 5) {
                    $isOnline = true;
                }
                $d->is_online_computed = $isOnline;
                return $d;
            });

        return response()->json([
            'success' => true,
            'drivers' => $drivers
        ]);
    }

    /**
     * Send a message to a driver.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $msg = SupportMessage::create([
            'driver_id' => $request->driver_id,
            'sender_type' => 'admin',
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Send Push Notification to Driver
        $driverUser = User::find($request->driver_id);
        if ($driverUser && $driverUser->fcm_token) {
            \App\Services\FirebasePushService::sendPush(
                'EuroTaxi Support', 
                'Admin: ' . \Illuminate\Support\Str::limit($request->message, 50),
                $driverUser->fcm_token,
                'chat'
            );
        }

        $isOnline = false;
        if ($driverUser) {
            $isOnline = (bool) $driverUser->is_online;
            if (!$isOnline && $driverUser->last_seen_at && \Carbon\Carbon::parse($driverUser->last_seen_at)->diffInMinutes(now()) <= 5) {
                $isOnline = true;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent.',
            'data' => [
                'id' => $msg->id,
                'driver_id' => $msg->driver_id,
                'sender_type' => $msg->sender_type,
                'sender_id' => $msg->sender_id,
                'message' => $msg->message,
                'attachment' => $msg->attachment,
                'is_read' => false,
                'created_at' => $msg->created_at ? $msg->created_at->toISOString() : now()->toISOString(),
                'time' => $msg->created_at ? $msg->created_at->format('h:i A') : now()->format('h:i A'),
                'human_time' => 'Just now',
            ],
            'driver_is_online' => $isOnline
        ]);
    }

    /**
     * Delete/Unsend a message sent by admin.
     */
    public function deleteMessage(Request $request, $id)
    {
        $type = $request->input('type', 'for_everyone');
        $msg = SupportMessage::where('id', $id)->where('sender_type', 'admin')->first();
        if ($msg) {
            if ($type === 'for_me') {
                $msg->hidden_by_admin = true;
                $msg->save();
            } else {
                $msg->delete();
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Message not found or unauthorized.'], 403);
    }

    /**
     * (Optional) Keep the old ticket-based show for backward compatibility if needed.
     */
    public function show($id)
    {
        return back();
    }
}

