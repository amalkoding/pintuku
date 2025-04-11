<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function register(Request $request)
    {
        $uid = $request->query('uid');

        if (empty($uid)) {
            return response('error', 400)->header('Content-Type', 'text/plain');
        }

        if (Card::where('uid', $uid)->exists()) {
            return response('already_registered', 200)->header('Content-Type', 'text/plain');
        }

        Card::create(['uid' => $uid]);
        return response('registered', 200)->header('Content-Type', 'text/plain');
    }

    public function check(Request $request)
    {
        $uid = $request->query('uid');

        if (empty($uid)) {
            return response('error', 400)->header('Content-Type', 'text/plain');
        }

        return response(
            Card::where('uid', $uid)->exists() ? 'registered' : 'not_registered',
            200
        )->header('Content-Type', 'text/plain');
    }

    public function fetch()
    {
        return response()->json(Card::orderBy('created_at', 'desc')->get());
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');

            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['success' => false, 'error' => 'Invalid ID'], 400);
            }

            $card = Card::find($id);
            if (!$card) {
                return response()->json(['success' => false, 'error' => 'Record not found'], 404);
            }

            $card->delete();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error('Delete error: ' . $e->getMessage(), [
                'id' => $request->input('id'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
