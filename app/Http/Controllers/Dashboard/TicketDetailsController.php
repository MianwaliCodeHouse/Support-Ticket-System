<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketDetail;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketDetailsController extends Controller
{
    public function show($id)
    {
        $ticket=Ticket::where('uuid',$id)->first();
        $ticket_details=TicketDetail::where('ticket_id',$ticket->id)->get();
        $ticket = Ticket::find($ticket->id);
        if (auth()->user()->hasRole('admin')) {
        return view('adminDashboard.tickets.ticketsDetails', ["ticket" => $ticket,'ticket_details'=>$ticket_details]);
        }else{
        return view('userDashboard.tickets.ticketsDetails', ["ticket" => $ticket,'ticket_details'=>$ticket_details]);  
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'message' => 'required'
        ]);
        try {
           $message=TicketDetail::create([
                'user_id' => Auth::user()->id,
                'ticket_id' => $request->ticket_id,
                'message' => $request->message,

            ]);
            return response()->json(['status'=>200,'data'=>$message]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>400,'data'=>$th]);
        }
    }

    public function close($id)
    {
        try {
            Ticket::find($id)->update([
                'status' => 'closed'
            ]);
            return response()->json(['status'=>200,'url'=>route('tickets.index')]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>400,'error'=>$th]);
        }
    }
}
