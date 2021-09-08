<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UnitOfMeasurement as UOM;

class UnitOfMeasurementController extends Controller
{

	public function uom()
	{
		return view('admin.uom');
	}

    public function all()
    {
    	$data = [
    		'name' => NULL,
    		'code' => NULL,
    		'action' => NULL,
    	];

    	$uom = UOM::where('active', 1)->get();

    	if(count($uom) > 0) {
    		$data = NULL;
    		foreach ($uom as $u) {
    			$data[] = [
    				'name' => $u->name,
    				'code' => $u->code,
    				'action' => '<a href="' . route('admin.update.uom', ['id' => $u->id]) . '" class="btn btn-warning btn-sm"><i class="pe-7s-pen"></i> Update</a>'
    			];
    		}
    	}

    	return $data;
    }


    public function add()
    {
        return view('admin.uom-add');
    }


    public function postAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:unit_of_measurements'
        ]);

        $uom = new UOM();
        $uom->name = $request->name;
        $uom->code = $request->code;
        $uom->description = $request->description;

        if($uom->save()) {
            return redirect()->route('admin.uom')->with('success', 'New Unit of Measurement Added!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }


    public function update($id)
    {
        $uom = UOM::findorfail($id);

        return view('admin.uom-update', ['uom' => $uom]);
    }


    public function postUpdate(Request $request)
    {
        $uom = UOM::findorfail($request->id);

        $request->validate([
            'name' => 'required'
        ]);

        if($uom->code != $request->code) {
            if(strcasecmp($uom->code, $request->code)) {
                $request->validate([
                    'code' => 'required|unique:unit_of_measurements' 
                ]);
            }
        }

        $uom->name = $request->name;
        $uom->code = $request->code;
        $uom->description = $request->description;

        if($uom->save()) {
            return redirect()->route('admin.uom')->with('success', 'Unit of Measurement Updated!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }
}
