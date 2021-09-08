<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Farm;

class FarmController extends Controller
{
    public  function farms()
    {
    	return view('admin.farms');
    }



    public function all()
    {
    	$data = [
    		'name' => NULL,
    		'code' => NULL,
    		'action' => NULL,
    	];

    	$farms = Farm::where('active', 1)->get();

    	if(count($farms) > 0) {
    		$data = NULL;
    		foreach ($farms as $f) {
    			$data[] = [
    				'name' => $f->name,
    				'code' => $f->code,
    				'action' => '<a href="' . route('admin.update.farm', ['id' => $f->id]) . '" class="btn btn-warning btn-sm"><i class="pe-7s-pen"></i> Update</a>'
    			];
    		}
    	}

    	return $data;
    }


    public function add()
    {
        return view('admin.farm-add');
    }


    public function postAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:farms'
        ]);

        $farm = new Farm();
        $farm->name = $request->name;
        $farm->code = $request->code;
        $farm->description = $request->description;

        if($farm->save()) {
            return redirect()->route('admin.farms')->with('success', 'New Farm Added!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }


    public function update($id)
    {
        $farm = Farm::findorfail($id);

        return view('admin.farm-update', ['farm' => $farm]);
    }


    public function postUpdate(Request $request)
    {
        $farm = Farm::findorfail($request->id);

        $request->validate([
            'name' => 'required'
        ]);

        if($farm->code != $request->code) {
            if(strcasecmp($farm->code, $request->code)) {
                $request->validate([
                    'code' => 'required|unique:farms' 
                ]);
            }
        }

        $farm->name = $request->name;
        $farm->code = $request->code;
        $farm->description = $request->description;

        if($farm->save()) {
            return redirect()->route('admin.farms')->with('success', 'Farm Updated!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }
}
