<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketDetails;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketDetailsController extends Controller
{
    public function index($id)
    {
        $ticket=Tickets::where('uuid',$id)->first();
        $ticket_details=TicketDetails::where('ticket_id',$ticket->id)->get();
        $ticket = Tickets::find($ticket->id);
        return view('dashboard.tickets.ticketsDetails', ["ticket" => $ticket,'ticket_details'=>$ticket_details]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'message' => 'required'
        ]);
        try {
           TicketDetails::create([
                'user_id' => Auth::user()->id,
                'ticket_id' => $request->ticket_id,
                'message' => $request->message,

            ]);
            return 1;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function close($id)
    {
        try {
            Tickets::find($id)->update([
                'status' => 'closed'
            ]);
            return 1;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
