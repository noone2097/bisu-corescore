<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Visitor;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Office $office = null)
    {
        $offices = Office::all();
        $selectedOffice = $office;
        $isOfficeLocked = !is_null($office);

        return view('evaluations.form', compact('offices', 'selectedOffice', 'isOfficeLocked'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_of_visit' => 'required|date',
            'time_of_visit' => 'required',
            'office_id' => 'required|exists:offices,id',
            'client_type' => 'required',
            'sex' => 'required',
            'region_of_residence' => 'required',
            'services_availed' => 'required',
            'served_by' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'signature' => 'required|string',
            'cc1' => 'required|integer',
            'cc2' => 'nullable|integer',
            'cc3' => 'nullable|integer',
            'responsiveness' => 'required|integer',
            'reliability' => 'required|integer',
            'access_facilities' => 'required|integer',
            'communication' => 'required|integer',
            'costs' => 'required|integer',
            'integrity' => 'required|integer',
            'assurance' => 'required|integer',
            'outcome' => 'required|integer',
            'commendations' => 'nullable|string',
            'suggestions' => 'nullable|string',
        ]);

        // Create visitor first
        $visitor = Visitor::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'signature' => $request->signature,
        ]);

        // Create evaluation with visitor_id
        $evaluation = new Evaluation($request->except(['first_name', 'last_name', 'signature']));
        $evaluation->visitor_id = $visitor->id;
        $evaluation->save();

        return redirect()->route('thank-you');
    }

    public function thankYou()
    {
        return view('evaluations.thank-you');
    }
}
