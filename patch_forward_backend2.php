<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/app/Http/Controllers/ChatController.php";
$content = file_get_contents($file);

$target1 = <<<EOF
            ->select(
                'm.id', 'm.message', 'm.created_at', 'm.read_at', 'm.reactions', 'm.reply_to_id',
                'm.from_user_id', 'm.attachment_path', 'm.attachment_type', 'm.attachment_name',
EOF;

$replacement1 = <<<EOF
            ->select(
                'm.id', 'm.message', 'm.created_at', 'm.read_at', 'm.reactions', 'm.reply_to_id',
                'm.from_user_id', 'm.attachment_path', 'm.attachment_type', 'm.attachment_name', 'm.is_forwarded',
EOF;
$content = str_replace($target1, $replacement1, $content);

$target2 = <<<EOF
                    'is_mine'         => $m->from_user_id == $currentUserId,
                    'sender'          => $m->sender_name,
EOF;

$replacement2 = <<<EOF
                    'is_mine'         => $m->from_user_id == $currentUserId,
                    'sender'          => $m->sender_name,
                    'is_forwarded'    => (bool)($m->is_forwarded ?? false),
EOF;
$content = str_replace($target2, $replacement2, $content);

$target3 = <<<EOF
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

$replacement3 = <<<EOF
        $id = DB::table('chat_messages')->insertGetId([
            'from_user_id'    => Auth::id(),
            'to_user_id'      => $request->to_user_id,
            'message'         => $messageText,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
            'reply_to_id'     => $request->reply_to_id,
            'is_forwarded'    => $request->forward_from_id ? 1 : 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
EOF;
$content = str_replace($target3, $replacement3, $content);

file_put_contents($file, $content);
echo "ChatController forward boolean patched successfully!";
