<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/app/Http/Controllers/ChatController.php";
$content = file_get_contents($file);

$target = <<<EOF
        $request->validate([
            'to_user_id'  => 'required|integer', // Allow 0
            'message'     => 'nullable|string|max:1000',
            'attachment'  => 'nullable|file|max:10240', // 10MB max
            'reply_to_id' => 'nullable|integer',
        ]);

        // If to_user_id != 0, must exist
        if ($request->to_user_id != 0) {
            $request->validate([
                'to_user_id' => 'exists:users,id'
            ]);
        }

        if (!$request->message && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
EOF;

$replacement = <<<EOF
        $request->validate([
            'to_user_id'      => 'required|integer', // Allow 0
            'message'         => 'nullable|string|max:1000',
            'attachment'      => 'nullable|file|max:10240', // 10MB max
            'reply_to_id'     => 'nullable|integer',
            'forward_from_id' => 'nullable|integer'
        ]);

        // If to_user_id != 0, must exist
        if ($request->to_user_id != 0) {
            $request->validate([
                'to_user_id' => 'exists:users,id'
            ]);
        }

        if (!$request->message && !$request->hasFile('attachment') && !$request->forward_from_id) {
            return response()->json(['error' => 'Message, attachment or forward ID is required'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;
        $attachmentName = null;
        $messageText = $request->message;

        if ($request->forward_from_id) {
            $origMsg = DB::table('chat_messages')->where('id', $request->forward_from_id)->first();
            if ($origMsg) {
                $messageText = $origMsg->message;
                $attachmentPath = $origMsg->attachment_path;
                $attachmentType = $origMsg->attachment_type;
                $attachmentName = $origMsg->attachment_name;
            }
        } elseif ($request->hasFile('attachment')) {
EOF;

$content = str_replace($target, $replacement, $content);

$target2 = <<<EOF
        $id = DB::table('chat_messages')->insertGetId([
            'from_user_id'    => Auth::id(),
            'to_user_id'      => $request->to_user_id,
            'message'         => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
            'reply_to_id'     => $request->reply_to_id,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
EOF;

$replacement2 = <<<EOF
        $id = DB::table('chat_messages')->insertGetId([
            'from_user_id'    => Auth::id(),
            'to_user_id'      => $request->to_user_id,
            'message'         => $messageText,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
            'reply_to_id'     => $request->reply_to_id,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
EOF;

$content = str_replace($target2, $replacement2, $content);

file_put_contents($file, $content);
echo "ChatController patched for forward support!";
