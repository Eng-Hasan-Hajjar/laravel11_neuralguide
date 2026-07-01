<?php
// app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'architecture_id' => 'required|exists:architectures,id',
            'body'            => 'required|string|min:5|max:1000',
            'rating'          => 'nullable|integer|min:1|max:5',
        ]);

        Comment::create([
            'user_id'         => auth()->id(),
            'architecture_id' => $data['architecture_id'],
            'body'            => $data['body'],
            'rating'          => $data['rating'] ?? null,
            'is_approved'     => false, // يحتاج موافقة المدير
        ]);

        return back()->with('status', 'تم إضافة تعليقك وهو بانتظار الموافقة');
    }

    public function destroy(Comment $comment)
    {
        abort_unless(auth()->id() === $comment->user_id || auth()->user()->role === 'admin', 403);
        $comment->delete();
        return back()->with('status', 'تم حذف التعليق');
    }
}
