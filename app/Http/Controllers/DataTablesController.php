<?php

namespace App\Http\Controllers;

use App\DataTables\User\UserTicketsDataTable;
use Illuminate\Http\Request;

class DataTablesController extends Controller
{
    public function userDataTable(UserTicketsDataTable $dataTable){
        return $dataTable->render('userDashboard.tickets.index');
    }
    public function adminDataTable(){

    }
}
