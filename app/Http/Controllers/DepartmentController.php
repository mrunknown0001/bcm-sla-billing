<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;

class DepartmentController extends Controller
{
    public function departments()
    {
    	return view('admin.departments');
    }


    public function all()
    {
    	$data = [
    		'name' => NULL,
    		'code' => NULL,
    		'action' => NULL,
    	];

    	$depts = Department::where('active', 1)->get();

    	if(count($depts) > 0) {
    		$data = NULL;
    		foreach ($depts as $d) {
    			$data[] = [
    				'name' => $d->name,
    				'code' => $d->code,
                    'action' => '<a href="' . route('admin.update.department', ['id' => $d->id]) . '" class="btn btn-warning btn-sm"><i class="pe-7s-pen"></i> Update</a>'
    			];
    		}
    	}

    	return $data;
    }



    public function add()
    {
        return view('admin.department-add');
    }



    public function postAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:departments'
        ]);

        $dept = new Department();
        $dept->name = $request->name;
        $dept->code = $request->code;
        $dept->description = $request->description;

        if($dept->save()) {
            return redirect()->route('admin.departments')->with('success', 'New Department Added!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }



    public function update($id)
    {
        $dept = Department::findorfail($id);

        return view('admin.department-update', ['dept' => $dept]);
    }


    public function postUpdate(Request $request)
    {
        $dept = Department::findorfail($request->id);

        $request->validate([
            'name' => 'required'
        ]);

        if($dept->code != $request->code) {
            if(strcasecmp($dept->code, $request->code)) {
                $request->validate([
                    'code' => 'required|unique:departments' 
                ]);
            }
        }

        $dept->name = $request->name;
        $dept->code = $request->code;
        $dept->description = $request->description;

        if($dept->save()) {
            return redirect()->route('admin.departments')->with('success', 'Department Updated!');
        }
        return redirect()->back()->with('error', 'Error Occured. Please Try Again!');
    }
}
