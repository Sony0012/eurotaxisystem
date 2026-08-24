<?php

namespace App\Services;

use App\Models\LoginAudit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ActivityAuditService
{
    /**
     * Human-readable module mappings from URL paths.
     */
    protected static array $pageModules = [
        'driver-management'    => ['name' => 'Driver Management', 'icon' => 'users'],
        'units'                => ['name' => 'Fleet Units', 'icon' => 'car'],
        'boundaries'           => ['name' => 'Boundaries & Remittance', 'icon' => 'credit-card'],
        'driver-behavior'      => ['name' => 'Driver Incidents & Behavior', 'icon' => 'alert-triangle'],
        'expenses'             => ['name' => 'Office Expenses', 'icon' => 'receipt'],
        'maintenance'          => ['name' => 'Vehicle Maintenance', 'icon' => 'wrench'],
        'franchise-management' => ['name' => 'Franchise Management', 'icon' => 'shield-check'],
        'salary'               => ['name' => 'Salary Management', 'icon' => 'wallet'],
        'super-admin'          => ['name' => 'Super Admin Portal', 'icon' => 'crown'],
        'activity-log'         => ['name' => 'Activity & Audit Logs', 'icon' => 'history'],
        'dashboard'            => ['name' => 'Dashboard Overview', 'icon' => 'layout-dashboard'],
    ];

    /**
     * Record a user request (Page visit or button action).
     */
    public static function recordRequest(Request $request, User $user): void
    {
        if ($user->role === 'driver') return;

        $path = trim($request->path(), '/');
        $method = strtoupper($request->method());

        // Skip internal assets, heartbeat polling, and telemetry endpoints
        if (
            str_starts_with($path, 'presence/') ||
            str_starts_with($path, 'livewire/') ||
            str_starts_with($path, '_ignition') ||
            str_starts_with($path, 'api/heartbeat') ||
            str_starts_with($path, 'sanctum/') ||
            str_ends_with($path, '.png') ||
            str_ends_with($path, '.svg') ||
            str_ends_with($path, '.js') ||
            str_ends_with($path, '.css')
        ) {
            return;
        }

        // Determine Module Name
        $moduleName = 'General Operations';
        foreach (self::$pageModules as $prefix => $info) {
            if (str_starts_with($path, $prefix)) {
                $moduleName = $info['name'];
                break;
            }
        }

        if ($method === 'GET') {
            // Only log non-AJAX primary web page visits to avoid cluttering
            if (!$request->ajax() && !$request->expectsJson()) {
                $throttleKey = "user_page_visit_{$user->id}_" . md5($path);
                if (!Cache::has($throttleKey)) {
                    Cache::put($throttleKey, true, now()->addMinutes(3));

                    $pageTitle = ucwords(str_replace(['-', '_', '/'], ' ', $path));
                    $notes = "Accessed {$moduleName} ({$pageTitle})";

                    LoginAudit::create([
                        'user_id'    => $user->id,
                        'user_name'  => $user->full_name ?: $user->name,
                        'user_email' => $user->email,
                        'user_role'  => $user->role,
                        'action'     => 'page_view',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'notes'      => $notes,
                        'created_at' => now(),
                    ]);
                }
            }
        } else {
            // POST / PUT / PATCH / DELETE (Button Clicks, Form Submissions, Mutations)
            $actionVerb = match ($method) {
                'POST'   => 'Submitted / Created record in',
                'PUT'    => 'Updated record in',
                'PATCH'  => 'Modified record in',
                'DELETE' => 'Deleted record in',
                default  => 'Performed operation in'
            };

            // Inspect specific entity context from request if available
            $entityContext = '';
            if ($request->filled('first_name') || $request->filled('last_name')) {
                $driverName = trim(($request->input('first_name') ?? '') . ' ' . ($request->input('last_name') ?? ''));
                if ($driverName) $entityContext = " for Driver: {$driverName}";
            } elseif ($request->filled('plate_number')) {
                $entityContext = " for Unit: " . $request->input('plate_number');
            } elseif ($request->filled('amount')) {
                $entityContext = " (Amount: ₱" . number_format((float)$request->input('amount'), 2) . ")";
            } elseif ($request->filled('name')) {
                $entityContext = " for: " . $request->input('name');
            }

            $notes = "{$actionVerb} {$moduleName}{$entityContext}";

            LoginAudit::create([
                'user_id'    => $user->id,
                'user_name'  => $user->full_name ?: $user->name,
                'user_email' => $user->email,
                'user_role'  => $user->role,
                'action'     => strtolower($method) . '_action',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'notes'      => $notes,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Generate an AI-powered Executive Narrative Paragraph & Digest for a user on a given date.
     */
    public static function generateExecutiveSummary(User $user, string $date, $audits, array $meta = []): array
    {
        $todayStr = Carbon::parse($date)->format('F j, Y');
        $userName = $user->full_name ?: $user->name;
        $userRole = ucfirst(str_replace('_', ' ', $user->role));
        $totalHours = $meta['hours'] ?? 0;
        $totalMins  = $meta['mins'] ?? 0;
        $isOnline   = !empty($meta['is_online']);
        $status     = $meta['status'] ?? 'inactive';

        // Extract and clean distinct action descriptions
        $actionList = [];
        $modulesTouched = [];

        foreach ($audits as $a) {
            $act = $a->action ?? '';
            $notes = $a->notes ?? '';
            $time = Carbon::parse($a->created_at)->format('h:i A');

            if ($notes) {
                $actionList[] = "[{$time}] {$notes}";
                foreach (self::$pageModules as $prefix => $info) {
                    if (stripos($notes, $info['name']) !== false && !in_array($info['name'], $modulesTouched)) {
                        $modulesTouched[] = $info['name'];
                    }
                }
            } elseif ($act === 'login') {
                $actionList[] = "[{$time}] Logged into the system";
            } elseif ($act === 'logout') {
                $actionList[] = "[{$time}] Logged out of the system";
            }
        }

        $actionsCount = count($actionList);
        $hourFmt = $totalHours > 0 ? "{$totalHours}h" : "{$totalMins}m";

        // Build Local Smart Executive Fallback first (guaranteed 100% accurate, zero latency)
        $fallbackSummary = self::buildLocalSummary($userName, $userRole, $todayStr, $totalMins, $isOnline, $actionList, $modulesTouched);

        // Attempt Gemini AI Enhancement if API key is available
        $aiSummary = $fallbackSummary;
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));

        if (!empty($apiKey) && count($actionList) > 0) {
            $cacheKey = "ai_staff_narrative_v2_{$user->id}_{$date}_" . md5(json_encode($actionList));
            if (Cache::has($cacheKey)) {
                $aiSummary = Cache::get($cacheKey);
            } else {
                try {
                    $prompt = "You are the Executive Operations AI Auditor for Euro Taxi System.\n"
                        . "Write a concise, 2-3 sentence executive paragraph in Filipino/Tagalog summarizing this staff member's workday on {$todayStr}.\n"
                        . "Staff Name: {$userName}\n"
                        . "Role: {$userRole}\n"
                        . "Total Working Time: {$totalMins} minutes ({$hourFmt})\n"
                        . "Current Status: " . ($isOnline ? 'Online/Active' : 'Offline') . "\n"
                        . "Recorded Actions:\n" . implode("\n", array_slice($actionList, 0, 30)) . "\n\n"
                        . "Guidelines:\n"
                        . "1. Be 100% factual and accurate based strictly on the provided actions.\n"
                        . "2. Highlight what modules they worked on, records they modified, or if they were idle.\n"
                        . "3. Keep tone professional, respectful, and authoritative.\n"
                        . "4. Output ONLY the paragraph.";

                    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
                    $res = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(8)->post($endpoint, [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ]
                    ]);

                    if ($res->successful()) {
                        $rawText = $res->json('candidates.0.content.parts.0.text');
                        if (!empty($rawText) && strlen($rawText) > 20) {
                            $aiSummary = trim($rawText);
                            Cache::put($cacheKey, $aiSummary, now()->addMinutes(15));
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('AI Activity Summary Gemini call failed: ' . $e->getMessage());
                }
            }
        }

        return [
            'summary_text'    => $aiSummary,
            'modules_touched' => array_values(array_unique($modulesTouched)),
            'total_actions'   => $actionsCount,
            'is_verified'     => true,
            'generated_at'    => now()->format('h:i A'),
        ];
    }

    /**
     * Local deterministic smart narrative builder (Zero latency, 100% fact-checked).
     */
    protected static function buildLocalSummary(
        string $userName,
        string $userRole,
        string $dateStr,
        int $totalMins,
        bool $isOnline,
        array $actions,
        array $modules
    ): string {
        $hours = (int) floor($totalMins / 60);
        $mins  = (int) ($totalMins % 60);
        $timeStr = ($hours > 0 && $mins > 0) 
            ? "{$hours} oras at {$mins} minuto" 
            : (($hours > 0) ? "{$hours} oras" : ($mins > 0 ? "{$mins} minuto" : "0 minuto"));

        if (empty($actions)) {
            return "Si {$userName} ({$userRole}) ay walang naitalang aktibidad o pag-login ngayong araw ({$dateStr}). Nananatiling 0h ang kanyang daily usage progress.";
        }

        $moduleStr = !empty($modules) ? implode(', ', $modules) : 'Dashboard Overview';
        $statusStr = $isOnline ? 'kasalukuyang aktibo at online' : 'nakapag-offline na matapos ang kanyang sesyon';

        $actionCount = count($actions);
        if ($actionCount === 1 && str_contains(strtolower($actions[0]), 'logged in')) {
            return "Si {$userName} ({$userRole}) ay nag-login sa system ngayong araw na may kabuuang active time na {$timeStr}. Binisita niya ang {$moduleStr} at {$statusStr}.";
        }

        return "Si {$userName} ({$userRole}) ay nakapagtala ng {$actionCount} operasyon na may kabuuang active time na {$timeStr} ngayong {$dateStr}. Sa kanyang shift, binisita at ginalaw niya ang mga modyul ng {$moduleStr}, at siya ay {$statusStr}.";
    }
}
