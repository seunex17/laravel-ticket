<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function list()
    {
        $user = Auth::user();

        if ($user->role === 'agent')
        {
            $agentId = $user->agent_id;
            $tickets = Ticket::where('agent_id', $agentId)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $tickets = Ticket::where('user_id', Auth::id())
                ->orderBy('id', 'desc')
                ->get();
        }

        return Response::json($tickets);
    }

    public function create(Request $request)
    : JsonResponse {
        if (Gate::allows('isAgent'))
        {
            return Response::json([
                'error' => 'Agent can not create ticket'
            ], 403);
        }

        $request->request->add([
            'user_id' => Auth::id(),
            'ticket_id' => time(),
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'agent_id' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()
                ->all();

            return Response::json([
                'error' => 'Validation error',
                'message' => array_shift($errors),
            ], 400);
        }

        Ticket::create($request->all());

        return Response::json([
            'message' => 'Your ticket has been submitted'
        ], 201);
    }

    public function view(string $id)
    : JsonResponse {
        $user = Auth::user();
        $ticket = Ticket::where([
            'ticket_id' => $id,
        ])->with('comments')->firstOrFail();

        return Response::json($ticket);
    }

    public function reply(Request $request)
    {
        $request->request->add([
            'user_id' => Auth::id(),
        ]);

        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'ticket_id' => 'required',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()
                ->all();

            return Response::json([
                'error' => 'Validation error',
                'message' => array_shift($errors),
            ], 400);
        }

        Comment::create($request->all());

        return Response::json([
            'message' => 'Your reply has been sent!'
        ], 201);
    }
}
