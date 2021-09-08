<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use App\JobOrder as Jo;

class JobOrderController extends Controller
{
    public function downloadJo($id)
    {
    	$jo = Jo::findorfail($id);

        $file = public_path()."/uploads/jo/" . $jo->attachment;
        $headers = array('Content-Type: application/pdf',);

        $filename = 'Attachment-' . $jo->attachment;

        return Response::download($file, $filename, $headers);
    }
}
