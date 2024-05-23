<?php

function store_image($path)
{
    return url('/') . $path;
}

function upload_image($path)
{

    return url('/') .'/uploads/'. $path;
}

function date_thai($dateTime)
{
	$thaiMonths = [
		1 => "มกราคม",
		2 => "กุมภาพันธ์",
		3 => "มีนาคม",
		4 => "เมษายน",
		5 => "พฤษภาคม",
		6 => "มิถุนายน",
		7 => "กรกฎาคม",
		8 => "สิงหาคม",
		9 => "กันยายน",
		10 => "ตุลาคม",
		11 => "พฤศจิกายน",
		12 => "ธันวาคม"
	];

	return date("d", strtotime($dateTime)) .' '. $thaiMonths[date("n", strtotime($dateTime))] .' '. (date("Y", strtotime($dateTime)) + 543);
}

function set_date_format($dateTime)
{
	return date("d-m-Y", strtotime($dateTime));
}

function set_date_time_format($dateTime)
{
	return date("d/m/Y H:i", strtotime($dateTime));
}

function set_date_format2($dateTime)
{
	return date("d/m/Y", strtotime($dateTime));
}

function set_time_format($dateTime)
{
	return date("H:i", strtotime($dateTime));
}

function flash_message($message = "ไม่สามารถทำรายการได้กรุณาลองใหม่ !", $status = false)
{
	$response = [
		'status' => $status,
		'message' => $message
	];

	return $response;
}

function public_status($status)
{
	return ($status) ? "<span class='label label-success'>เผยแพร่</span>" : "<span class='label label-danger'>ไม่ได้เผยแพร่</span>";
}

function user_token_expire_date()
{
	return date("Y-m-d", strtotime("+90 days"));
}

function product_price_format($price)
{
	return @number_format($price) . ' THB';
}

function myorder_price_format($price)
{
	return @number_format($price, 2) . ' บาท';
}

function get_member_type($cusNick)
{
	$rest = substr($cusNick, 4, 1);

	return ($rest == 'D' || $rest == 'd') ? 'doctor' : 'clinic';
}

function role_member($role = null)
{
	$roles = [
		2 => 'Accounting',
		3 => 'Sale',
		4 => 'Marketing'
	];

	if ($role) {

		return $roles[$role];
	}

	return $roles;
}

function type_customer($type = null)
{
	$types = [
		'doctor' => 'แพทย์',
		'clinic' => 'องค์กร/บริษัท/คลีนิค/โรงบาล'
	];

	if ($type) {

		return $types[$type];
	}

	return $types;
}

function order_number($number = 0)
{
	return str_pad($number, 6, '0', STR_PAD_LEFT);
}

function order_status($status = null)
{
     //new, payment_request, pending_check, pending_shiping, delivered, canceled 
     $array = [
        'new' => "<span class='label label-primary'>Incoming</span>",
        'success' => "<span class='label label-success'>Confirmed</span>"
     ];

     $arrayList = [
        'new' => "Incoming",
        'success' => "Confirmed"
     ];

     return ($status) ? $array[$status] : $arrayList;
}

function pickup_status($status)
{
	$array = [
		0 => "<span class='label label-danger'>Open</span>",
		1 => "<span class='label label-success'>Received</span>"
	];

	return $array[$status];
}

function redemption_status($payment)
{

	if($payment->payment_type == "online"){
		$array = [
			0 => "<span class='label label-danger'>Pending</span>",
			1 => "<span class='label label-success'>Approved</span>"
		];
		return $array[$payment->is_success];
	}
	elseif(($payment->payment_type == "transfer") || ($payment->payment_type == "qr credit")){

		if($payment->is_transfer_confirmed){
			if($payment->is_success){
				$status = "<span class='label label-success'>Approved</span>";
			}
			else{
				$status = "<a href='admin/transfer/file?payment_transaction_id=$payment->id' target='_blank'><span class='label label-warning'>Waiting</span></a>";
			}
			
		}
		else{
			$status = "<span class='label label-danger'>Pending</span>";
		}
		return $status;
	}

	
}


function removeComma($string)
{
	return str_replace(",", "", $string);
}

function pickup_time($time = null)
{
	$times = [
		'08.00-12.00' => 'Morning (08.00-12.00)',
		'12.00-18.00' => 'Afternoon (12.00-18.00)',
		'18.00-24.00' => 'Evening (18.00-24.00)'
	];

	if ($time) {

		return $times[$time];
	}

	return $times;
}

function type_promotion($type = null)
{
	$types = [
		1 => 'Mobile App',
		2 => 'Web App',
	];

	if ($type) {

		return $types[$type];
	}

	return $types;
}