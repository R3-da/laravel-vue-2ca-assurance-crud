<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Claim;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function index(Claim $claim)
    {
        $attachments = $claim->attachments;
        return response()->json($attachments);
    }

    public function store(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'filename' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $attachment = Attachment::create($validated);
        $claim->attachments()->attach($attachment->id);

        return response()->json($attachment, 201);
    }

    public function destroy(Claim $claim, Attachment $attachment)
    {
        $claim->attachments()->detach($attachment->id);
        $attachment->delete();

        return response()->json(null, 204);
    }
}