<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Closure;

use App\JobOrder as Jo;
use App\WorkOrder as Wo;
use App\Farm;

use PDF;

use App\Http\Controllers\GeneralController as GC;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function(Request $request, Closure $next){
            if(Auth::user()->dept_id != NULL) {
                if(Auth::user()->dept_id == 1  || Auth::user()->dept_id == 2) {
                    return $next($request);
                }
                else {
                    return abort(403);
                }
            }
            else {
                return abort(403);
            }
        });

    }

    public function index()
    {
    	$farms = Farm::where('active', 1)->get();

        $jo_result = Jo::select(DB::raw('YEAR(date_of_request) as year'))->distinct()->get();
        $jo_years = $jo_result->pluck('year');

        $wro_result = Wo::select(DB::raw('YEAR(date_of_request) as year'))->distinct()->get();
        $wro_years = $wro_result->pluck('year');

        $years = $jo_years->merge($wro_years);

        $years = $years->unique();

    	return view('includes.reports.index', ['farms' => $farms, 'years' => $years]);
    }


    public function generateDownload(Request $request)
    {
        $request->validate([
            'document_type' => 'required',
            'year' => 'required',
            'month' => 'required|numeric|between:1,12',
        ]);

        if($request->document_type != 'jo' && $request->document_type != 'wro') {
            return abort(500);
        }


        if($request->document_type == 'jo') {
            $pending = Jo::where('status', 1)
                ->whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->get();

            $approved1 = Jo::where('status', 2)
                ->whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->get();

            $cancelled = Jo::where('status', 3)
                ->whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->get();

            $disapproved = Jo::where('status', [7, 4])
                ->whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->get();

            $approved2 = Jo::where('status', 6)
                ->whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->get();


            $jo_total =  Jo::whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->count();

            $data = [
                'pending' => count($pending),
                'approved1' => count($approved1),
                'cancelled' => count($cancelled),
                'disapproved' => count($disapproved),
                'approved2' => count($approved2),
                'year' => $request->year,
                'month' => GC::getMonth($request->month),
                'jo_total' => $jo_total 
            ];



            view()->share('data', $data);
            $pdf = PDF::loadView('jo_status_summary', $data);

            $filename = 'JO-' . GC::getMonth($request->month) . ' ' . $request->year . '.pdf';

            return $pdf->download($filename);
        }
        else {
            $pending = Wo::where('approval_sequence', 1)
                        ->where('manager_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $manager_approved = Wo::where('approval_sequence', 2)
                        ->where('div_head_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $divhead_approved = Wo::where('approval_sequence', 3)
                        ->where('bcm_manager_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $bcm_manager_approved = Wo::where('approval_sequence', 4)
                        ->where('gen_serv_div_head_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $gen_serv_div_head_approved = Wo::where('approval_sequence', 5)
                        ->where('treasury_manager_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $treasury_manager_approved = Wo::where('approval_sequence', 6)
                        ->where('coo_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $coo_approved = Wo::where('approval_sequence', 7)
                        ->where('vp_gen_serv_approval', 0)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $vp_gen_serv_approved = Wo::where('approval_sequence', 8)
                        ->where('vp_gen_serv_approval', 1)
                        ->where('cancelled', 0)
                        ->where('disapproved', 0)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $cancelled = Wo::where('cancelled', 1)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $disapproved = Wo::where('disapproved', 1)
                        ->whereYear('date_of_request', '=', $request->year)
                        ->whereMonth('date_of_request', '=', $request->month)
                        ->get();

            $wro_total =  Wo::whereYear('date_of_request', '=', $request->year)
                  ->whereMonth('date_of_request', '=', $request->month)
                  ->count();


            $data = [
                'pending' => count($pending),
                'cancelled' => count($cancelled),
                'disapproved' => count($disapproved),
                'manager_approved' => count($manager_approved),
                'divhead_approved' => count($divhead_approved),
                'bcm_manager_approved' => count($bcm_manager_approved),
                'gen_serv_div_head_approved' => count($gen_serv_div_head_approved),
                'treasury_manager_approved' => count($treasury_manager_approved),
                'coo_approved' => count($coo_approved),
                'vp_gen_serv_approved' => count($vp_gen_serv_approved),
                'year' => $request->year,
                'month' => GC::getMonth($request->month),
                'wro_total' => $wro_total
            ];

            view()->share('data', $data);
            $pdf = PDF::loadView('wro_status_summary', $data);

            $filename = 'WRO-' . GC::getMonth($request->month) . ' ' . $request->year . '.pdf';

            return $pdf->download($filename);
        }
    }




    public function allJo($from = null, $to = null, $status = null)
    {
    	$data = [
    		'jo' => NULL,
    		'status' => NULL,
    		'date_of_request' => NULL,
    		'action' => NULL,
    	];

    	if($from == null || $to == null) {
            if($status == null || $status == '') {
                $jos = Jo::all();                
            }
            else {
                $jos = Jo::where('status', $status)->get();
            }
    	}
    	elseif($from != null && $to != null) {
    		// $jos = Jo::whereBetween('created_at', [$from, $to])->get();
            if($status == null || $status == '') {
                $jos = Jo::whereDate('date_of_request', '>=', $from)
                ->whereDate('date_of_request', '<=', $to)
                ->get();    
            }
            else {
                $jos = Jo::whereDate('date_of_request', '>=', $from)
                ->whereDate('date_of_request', '<=', $to)
                ->where('status', $status)
                ->get();
            }
    		
    	}
    	
    	if(count($jos) > 0) {
    		$data = NULL;
    		foreach($jos as $j) {
	    		$data[] = [
	    			'jo' => $j->jo_no,
	    			'status' => GC::viewJoStatus($j->status),
	    			'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
	    			'action' => $this->joAction($j->status, $j->jo_no, $j->id)
	    		];
    		}
    	}

    	return $data;
    }


    public function joStatus($status = null)
    {
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'action' => NULL,
        ];

       $jos = Jo::where('status', $status)->get();

        if(count($jos) > 0) {
            $data = NULL;
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'status' => GC::viewJoStatus($j->status),
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'action' => $this->joAction($j->status, $j->jo_no, $j->id)
                ];
            }
        }
 
        return $data;
    }


    public function allWro($from = null, $to = null, $status = null)
    {
    	$data = [
    		'wro' => NULL,
    		'status' => NULL,
    		'date_of_request' => NULL,
    		'action' => NULL,
    	];

    	if($from == null || $to == null) {
    		
            if($status == null || $status == '') {
                $wro = Wo::all();             
            }
            elseif($status == 'cancelled') {
                $wro = Wo::where('cancelled', 1)->get();
            }
            elseif($status == 'disapproved') {
                $wro = Wo::where('disapproved', 1)->get();
            }
            else {
                $wro = Wo::where('approval_sequence', $status)->get();
            }
    	}
    	elseif($from != null && $to != null) {
    		// $wro = Wo::whereBetween('created_at', [$from, $to])->get();

            if($status == null || $status == '') {
                $wro = Wo::whereDate('date_of_request', '>=', $from)
                ->whereDate('date_of_request', '<=', $to)
                ->get();         
            }
            elseif($status == 'cancelled') {
                $wro = Wo::whereDate('date_of_request', '>=', $from)
                ->whereDate('date_of_request', '<=', $to)
                ->where('cancelled', 1)
                ->get();
            }
            elseif($status == 'disapproved') {
                $wro = Wo::whereDate('date_of_request', '>=', $from)
                ->whereDate('date_of_request', '<=', $to)
                ->where('disapproved', 1)
                ->get();
            }
            else {
                $wro = Wo::where('approval_sequence', $status)
                    ->whereDate('date_of_request', '>=', $from)
                    ->whereDate('date_of_request', '<=', $to)
                        ->get();
            }
    	}

    	if(count($wro) > 0) {
    		$data = NULL;
    		foreach($wro as $w) {
    			$data[] = [
    				'wro' => $w->wr_no,
    				'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
    				'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
    				'action' => $this->wroAction($w->approval_sequence, $w->wr_no, $w->id)
    			];
    		}
    	}

    	return $data;
    }



    public function wroStatus($status)
    {

        $data = [
            'wro' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'action' => NULL,
        ];

        if($status == null || $status == '') {
            $wro = Wo::all();             
        }
        elseif($status == 'cancelled') {
            $wro = Wo::where('cancelled', 1)->get();
        }
        elseif($status == 'disapproved') {
            $wro = Wo::where('disapproved', 1)->get();
        }
        else {
            $wro = Wo::where('approval_sequence', $status)->get();
        }

        if(count($wro) > 0) {
            $data = NULL;
            foreach($wro as $w) {
                $data[] = [
                    'wro' => $w->wr_no,
                    'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                    'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                    'action' => $this->wroAction($w->approval_sequence, $w->wr_no, $w->id)
                ];
            }
        }

        return $data;
    }




    public function viewJo($id)
    {
    	$jo = Jo::findorfail($id);

    	return view('includes.reports.view-jo', ['jo' => $jo]);
    }




    public function viewWro($id)
    {
    	$wro = Wo::findorfail($id);

    	return view('includes.reports.view-wro', ['wro' => $wro]);
    }













    private function joAction($status, $jo, $id)
    {
    	// if($status == 2 || $status == 6) {
    	if(false) {
    		return '<button id="view" data-id="' . $id . '" data-text="Do you want to view Job Order ' . $jo . '?" class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <a href="' . route("manager.jo.pdf.download", ['id' => $id]) . '" class="btn btn-primary btn-xs"><i class="pe-7s-download"></i> Download</a>';
    	}
    	else {
    		return '<button id="view" data-id="' . $id . '" data-text="Do you want to view Job Order ' . $jo . '?" class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button>';
    	}
    }



    private function wroAction($status, $wro, $id)
    {
		return '<button id="view2" data-id="' . $id . '" data-text="Do you want to view Work Request Order ' . $wro . '?" class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button>';
    }

}
