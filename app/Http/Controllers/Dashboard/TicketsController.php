<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TicketsController extends Controller
{
    public function index()
    {
        $students = User::role('student')->get();
        return view('dashboard.tickets.index', ['students' => $students]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required | max:255',
            'description' => 'required'
        ]);
        try {
            Ticket::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return 1;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function data(Request $request, $id = null)
    {
        $query = Ticket::select(['id','title','uuid','description','status','created_at','user_id']);
        if ($id) {
            $query->where('user_id', $id);
        }
        // Check if a student filter is applied
        if ($request->has('student_filter') && $request->student_filter != '') {
            $query->where('user_id', $request->student_filter);
        }
        return DataTables::of($query)
            ->addIndexColumn() // This will automatically add the index column
            ->addColumn('student_name', function ($ticket) {
                return $ticket->user ? $ticket->user->name : 'N/A';
            })
            ->addColumn('created_at', function ($ticket) {
                return $ticket->created_at ? \Carbon\Carbon::parse($ticket->created_at)->format('d F Y') : 'N/A';
            })
            ->addColumn('status', function ($ticket) {
                if ($ticket->status == 'pending') {


                    return '<span class="bg-yellow-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Pending</span>';
                } elseif ($ticket->status == 'in-progress') {
                    return '<span class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                In Progress
                            </span>';
                } elseif ($ticket->status == 'closed') {
                    return ' <span class="bg-gray-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                Closed
                            </span>';
                }
            })
            ->addColumn('actions', function ($ticket) {
                if (auth()->user()->hasRole('admin')) {
                    if ($ticket->status == 'pending') {


                        return '<button onclick="acceptTicket(' . $ticket->id . ')" class="text-sm bg-slate-700 text-white py-2 px-4 rounded">Accept</button>
                    
                    <button class="bg-red-500 text-white py-2 px-4 rounded ml-2" onclick="destroy(' . $ticket->id . ')">Delete</button>';
                    } else {
                        return '<a href="' . route('ticket-details.show', $ticket->uuid) . '" class="text-sm bg-slate-700 text-white py-2 px-4 rounded">View Details</a>
                    
                    <button class="text-sm bg-red-500 text-white py-2 px-4 rounded ml-2" onclick="destroy(' . $ticket->id . ')">Delete</button>';
                    }
                } else {
                    if ($ticket->status == 'pending') {
                        return '<button class="bg-slate-700 text-white py-2 px-4 rounded ml-2" onclick="openEditModel(' . htmlspecialchars(json_encode($ticket)) . ')">Edit</button>
                    <button class="bg-red-500 text-white py-2 px-4 rounded ml-2" onclick="destroy(' . $ticket->id . ')">Delete</button>';
                    }else{

                    
                    return '<a href="' . route('ticket-details.show', $ticket->uuid) . '" class="text-sm bg-slate-700 text-white py-2 px-4 rounded">View Details</a>
                    <button class="bg-slate-700 text-white py-2 px-4 rounded ml-2" onclick="openEditModel(' . htmlspecialchars(json_encode($ticket)) . ')">Edit</button>
                    <button class="bg-red-500 text-white py-2 px-4 rounded ml-2" onclick="destroy(' . $ticket->id . ')">Delete</button>';
                }
                }
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }
    public function destroy($id)
    {
        try {
            Ticket::find($id)->delete();
            return 1;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);
        try {
            Ticket::findOrFail($id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return 1;
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function accept($id)
    {
        try {
            Ticket::find($id)->update([
                'status' => 'in-progress'
            ]);
            return 1;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
