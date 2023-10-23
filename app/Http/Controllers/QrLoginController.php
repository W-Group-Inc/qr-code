<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use App\User;
use App\Employee;
use App\Attendance;
class QrLoginController extends Controller
{
    public function index(Request $request) {
    	
		return view('auth.QrLogin');
	}
	public function indexoption2(Request $request) {
		$attendances = Attendance::orderBy('updated_at','desc')->take(20)->get();
    	
		return view('auth.QrLogin2',
		array(
			'attendances' => $attendances,
		));
	}
	public function ViewUserQrCode($value='')
	{
		return view('backEnd.users.viewqrcode');
	}
	public function checkUser(Request $request) {
		$attendances = [];
		 $result =0;
			if ($request->data) {
				$user = Employee::where('id',$request->data)->first();
				$date_from = date("Y-m-d H:i:s");
				$time = strtotime($date_from);
				$time = $time - (1 * 60);
				$date_to = date("Y-m-d H:i:s", $time);
				if ($user) {
					$attendance = Attendance::where('employee_id',$request->data)->whereBetween('updated_at',[$date_to,$date_from])->first();
					if($attendance == null)
					{
						$attendances = Attendance::where('employee_id',$request->data)->where('date',date('Y-m-d'))->where('break_in',null)->first();
						if($attendances == null)
						{
							$attendances = new Attendance;
							$attendances->employee_id = $request->data;
							$attendances->break_out = date('Y-m-d H:i');
							$attendances->date = date('Y-m-d');
							$attendances->save();
						}
						else
						{
							$attendances->break_in = date('Y-m-d H:i');
							$attendances->save();
						}
					}
				    $result =1;
				 }else{
				 	$result =0;
				 }
			}
			return array(
				'user' => $user,
				'attendance' => $attendances,
			);
	}
	public function checkUserTest($id) {
		$attendances=[];
		 $result =0;
			if ($id) {
				$user = Employee::where('id',$id)->first();
				$date_from = date("Y-m-d H:i:s");
				$time = strtotime($date_from);
				$time = $time - (1 * 60);
				$date_to = date("Y-m-d H:i:s", $time);
				if ($user) {
					$attendance = Attendance::where('employee_id',$id)->whereBetween('created_at',[$date_to,$date_from])->first();
					if($attendance == null)
					{
						$attendances = Attendance::where('employee_id',$id)->where('date',date('Y-m-d'))->where('break_in',null)->first();
						if($attendances == null)
						{
							$attendances = new Attendance;
							$attendances->employee_id = $id;
							$attendances->break_out = date('Y-m-d H:i');
							$attendances->date = date('Y-m-d');
							$attendances->save();
						}
						else
						{
							$attendances->break_in = date('Y-m-d H:i');
							$attendances->save();
						}
					}
				    $result =1;
				 }else{
				 	$result =0;
				 }
			}
			return array(
				'user' => $user,
				'attendance' => $attendances,
			);
	}

	public function QrAutoGenerate(Request $request)
	{	
		$result=0;
		if ($request->action = 'updateqr') {
			$user = Sentinel::getUser();
			if ($user) {
				$qrLogin=bcrypt($user->personal_number.$user->email.str_random(40));
		        $user->QRpassword= $qrLogin;
		        $user->update();
		        $result=1;
			}
		
		}
		
        return $result;
	}

}