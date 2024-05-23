<?php

function map_promotion_response($promotions)
{
	$data = [];
	foreach ($promotions as $key => $value) {
		$data[] = [
            'id' => $value->id,
            'image' => store_image($value->image),
            'text' => $value->text,
            'small_text' => $value->small_text,
            'url' => $value->url
		];
	}

	return $data;
}

function map_product_response($products, $memberId = 0)
{
    $data = [];
	foreach ($products as $key => $value) {
		$data[] = [
            'id' => $value->id,
            'image' => store_image($value->image),
            'name' => $value->name,
            'price' => product_price_format($value->price),
            'description' => $value->description,
            'details' => $value->details,
            'sizes' => explode(',', $value->sizes),
            'colors' => explode(',', $value->colors),
            'favorite_status' => ($value->memberFavorite($memberId)) ? true : false
		];
	}

	return $data;
}

function map_data_favorite($favorites)
{
    $data = [];
	foreach ($favorites as $key => $value) {
        if ($value->product) {
            $data[] = [
                'product' => [
                'id' => $value->product->id,
                'image' => store_image($value->product->image),
                'name' => $value->product->name,
                'price' => product_price_format($value->product->price),
                'description' => $value->product->description,
                'favorite_status' => true
            ],
            'favorite' => [
                'product_id' => $value->product_id,
                'member_id' => $value->member_id,
                ]
            ];
        }
	}

	return $data;
}

function map_data_product_history($histories)
{
    $data = [];
	foreach ($histories as $key => $value) {
        if ($value->product) {
            $data[] = [
                'product' => [
                    'id' => $value->product->id,
                    'image' => store_image($value->product->image),
                    'name' => $value->product->name,
                    'price' => product_price_format($value->product->price),
                    'description' => $value->product->description
                ],
            ];
        }
	}

	return $data;
}

function map_voucher_slide_response($banners)
{
    $data = [];
	foreach ($banners as $key => $value) {
		$data[] = [
            'id' => $value->id,
            'image' => store_image($value->image),
            'url' => $value->url,
            'name' => $value->name,
            'sort' => $value->sort
		];
	}

	return $data;
}

function map_voucher_response($vouchers)
{
    $data = [];
	foreach ($vouchers as $key => $value) {
		$data[] = [
            'id' => $value->id,
            'image' => store_image($value->image),
            'name' => $value->name
		];
	}

	return $data;
}

function map_voucher_cart_response($vouchers)
{
    $data = [];
	foreach ($vouchers as $key => $value) {
		$data[] = [
            'id' => $value->id,
            'voucher_id' => $value->voucher_id,
            'image' => store_image($value->voucher->image),
            'name' => $value->voucher->name,
            'point' => $value->point,
            'voucher_value' => $value->voucher_value
		];
	}

	return $data;
}

function map_eorder_response($results, $status = 'active')
{
    $orders = [];
    $data = json_decode($results, true);
    $status = ($status == 'active') ? 'Active' : 'Complete';
    foreach ($data['data'] as $key => $value) {
        if ($value['ord_stat'] == $status) {
            
            $product_type_of_work = '';
            foreach ($value['product'] ?? [] as $index => $product) {

                $product_type_of_work .= ($index > 0) ? '/' . $product['pord_name'] : $product['pord_name'];
            }

            $orders[] = [
                'id' => $value['e_id'],
                'type' => $product_type_of_work,
                'status' => $value['stat'],
                'cus_name' => $value['cus_name'],
                'doc_name' => $value['doc_name'],
                'code' => $value['e_code'],
                'patient_name' => $value['pat_name'],
                'finish_date' => set_date_format2($value['finish_date'])
            ];
        }
    }

    return $orders;
}

function map_eorder_detail_response($result)
{
    $jsonDecode = json_decode($result, true);

    //dd($jsonDecode);

    //return $jsonDecode;

    foreach ($jsonDecode['data'] as $key => $value) {
        return [
            'id' => $value['e_id'],
            'code' => $value['e_code'],
            'type' => $value['type'],
            'status' => $value['stat'],
            'cus_name' => $value['cus_name'],
            'doc_name' => $value['doc_name'],
            'patient_name' => $value['pat_name'],
            'entry' => $value['enty_date'],
            'trans' => null,
            'finish' => $value['finish_date'],
            'price' => myorder_price_format($value['price'], 2),
            'status' => order_process_stats($value['lst_stat']),
            'products' => map_order_product_response($value['product']),
            'technical' => '-',
            'shipping_company' => $value['shipping_company'],
            'tracking_number' => $value['tracking_number'] ,
            'link_tracking' => $value['link_tracking']
        ];
    }
}

function order_process_stats($status)
{
    $data = [];
    foreach ($status ?? [] as $key => $value) {
        $data[] = [
            'name' => $value['section'],
            'actived' => true,
        ];
    }

    return $data; 
}

function map_order_product_response($products)
{
    return $products;
}

function map_json_respone($arrayJson)
{
    $data = [];
    foreach ($arrayJson as $key => $value) {
        if ($value) {
            $data[] = ['value' => $value];
        }
    }

    return $data;
}

function map_response_pickups($orderPickups)
{
    $data = [];
    
    foreach ($orderPickups as $key => $value) {
        $data[] = [
            'id' => $value->id,
            'address' => $value->member->name ?? '' .' '. $value->address,
            'time' => date('d/m/Y', strtotime($value->created_at)) .' '. $value->time,
            'customer_name' => $value->member->name ?? '',
            'lat' => $value->lat ?? 18.7938681,
            'long' => $value->long ?? 98.816391,
            'note' => $value->note ?? '',
            'doctor_name' => $value->doctor_name ?? '',
            'patient_name' => $value->patient_name ?? ''
        ];
    }

    return $data;
}

function map_response_check_ins($orderPickups)
{
    $data = [];
    
    foreach ($orderPickups as $key => $value) {
        $data[] = [
            'id' => $value->id,
            'address' => $value->address,
            'time' => $value->time
        ];
    }

    return $data;
}

function map_response_carts($carts){
    
    $data = [];

    foreach ($carts as $key => $value) {
        if ($value->product) {
            $data[] = [
                'product' => [
                    'id' => $value->product->id,
                    'image' => store_image($value->product->image),
                    'name' => $value->product->name,
                    'price' => product_price_format($value->product->price),
                    'description' => $value->product->description,
                    'details' => $value->product->details,
                    'sizes' => explode(',', $value->product->sizes),
                    'colors' => explode(',', $value->product->colors)
                ],
                'cart' => [
                    'id' => $value->id,
                    'product_id' => $value->product_id,
                    'qty' => $value->qty,
                    'price' => $value->price,
                    'total' => $value->total,
                    'size' => $value->size,
                    'color' => $value->color
                ]
            ];
        }
    }

    return $data;
}

function map_response_orders($orders, $type = 'short')
{
    $data = [];

    if ($type == 'short') {

        foreach ($orders as $key => $order) {
            $data[] = [
                'id' => $order->id,
                'number' => order_number($order->id),
                'date' => date('d/m/Y', strtotime($order->date)),
                'amout_total' => product_price_format($order->total)
            ];
        }
    } else {
        
        foreach ($orders as $key => $order) {
            $data[] = [
                'id' => $order->id,
                'number' => order_number($order->id),
                'date' => date('d/m/Y', strtotime($order->date)),
                'amout_total' => product_price_format($order->total),
                'customer_name' => $order->member->name ?? ''
            ];
        }
    }

    return $data;
}

function map_response_order($order)
{   
    $data['order'] = [
        'id' => $order->id,
        'number' => order_number($order->id),
        'date' => date('d/m/Y', strtotime($order->date)),
        'amout_total' => product_price_format($order->total),
        'customer' => $order->member->name,
        'phone' => $order->phone,
        'address' => $order->address
    ];

    $data['products'] = [];
    foreach ($order->products ?? [] as $key => $item) {
        $data['products'][] = [
            'id' => $item->product_id,
            'name' => $item->product->name . ' / size: ('. $item->size .') color: ('. $item->color .')',
            'qty' => $item->qty,
            'price' => $item->price,
            'total' => $item->total
        ];
    }

    return $data;
}

function map_response_checking($checkings)
{
    $data = [];
    
    foreach ($checkings as $key => $value) {
        $data[] = [
            'id' => $value->id,
            'address' => $value->location,
            'time' => $value->time,
            'lat' => $value->lat,
            'long' => $value->long,
            'note' => $value->note
        ];
    }

    return $data;
}

function map_member_vouchers($vouchers)
{
    $data = [];
    
    foreach ($vouchers as $key => $value) {
        $data[] = [
            'id' => $value->id,
            'point' => $value->point,
            'amount' => $value->amount,
            'code' => $value->id,
            'approved' => $value->approved,
            'date' => set_date_format($value->created_at),
            'voucher' => [
                'id' => $value->voucher->id,
                'image' => store_image($value->voucher->image),
                'name' => $value->voucher->name,
                'exchange_rate' => $value->voucher->exchange_rate,
                'voucher_value' => $value->voucher->voucher_value,
                'description' => $value->voucher->description
            ]
        ];
    }

    return $data;
}

function map_invoice_response($invoices, $type = 'all', $ids = [])
{
    $data['invoices'] = [];
    $amountTotal = 0;
    $pastAmountTotal = 0;
    foreach ($invoices as $key => $value) {

        if ($type == 'past_due_date') {

            if ($value['due_date'] < date('Y-m-d')) {

                $data['invoices'][] = [
                    'id' => $value['id'],
                    'number' => $value['number'],
                    'due_date' => set_date_format($value['due_date']),
                    'amount_total' => @number_format($value['amount_total'], 2),
                    'amount_total_origin' => $value['amount_total'],
                    'over_due_date' => ($value['due_date'] < date('Y-m-d')) ? true : false,
                    'status' => $value['state']
                ];
                
                $amountTotal += $value['amount_total'];
                
                ($value['due_date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total'] : '';

            }
                
        } else {
   
            $data['invoices'][] = [
                'id' => $value['id'],
                'number' => $value['number'],
                'due_date' => set_date_format($value['due_date']),
                'amount_total' => @number_format($value['amount_total'], 2),
                'amount_total_origin' => $value['amount_total'],
                'over_due_date' => ($value['due_date'] < date('Y-m-d')) ? true : false,
                'status' => $value['state']
            ];
            
            $amountTotal += $value['amount_total'];
            
            ($value['due_date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total'] : '';
        
        }
    }

    $lists = [];
    if (count($ids)) {

        $amountTotal = 0;
        $pastAmountTotal = 0;

        foreach ($data['invoices'] as $key => $value) {

            if (in_array($value['id'], $ids)) {

                $lists[] = $value;
                
                $amountTotal += $value['amount_total_origin'];    
                ($value['due_date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total_origin'] : '';
            }
        }

        $data['invoices'] = $lists;

    }

    $data['amount_total'] = @number_format($amountTotal, 2);
    $data['past_amount_total'] = @number_format($pastAmountTotal, 2);

    return $data;
}

function map_payment_member_response($payments)
{
    $data = [];
    foreach ($payments as $key => $value) {
        $data[] = [
            'id' => $value->id,
            'date' => set_date_format($value->created_at),
            'reference' => $value->ref1,
            'amount_total'  => @number_format(removeComma($value->total), 2),
            'status' => $value->is_success
        ];
    }

    return $data;
}

function map_payment_accounting_response($payments)
{
    $data = [];
    foreach ($payments as $key => $value) {
        $bills = json_decode($payments['bill_content'], true);
        $billIds = "";
        foreach ($bills as $bill) {
            $billIds .= " " . $bill['number'];
        }
        $data[] = [
            'id' => $value->id,
            'bill_ids' => $billIds,
            'ref_code' => $value->ref1,
            'customer_name' => $value->member->name,
            'date' => set_date_format($value->created_at),
            'amount'  => @number_format(removeComma($value->total), 2),
            'status' => $value->is_success
        ];
    }

    return $data;
}

function map_bill_issue_response($bills, $type = 'all', $ids = [])
{
    $data['invoices'] = [];
    $amountTotal = 0;
    $pastAmountTotal = 0;
    foreach ($bills as $key => $value) {

        if (!empty($value['id'])) {

            if ($type == 'past_due_date') {

                if ($value['date'] < date('Y-m-d')) {

                    $data['invoices'][] = [
                        'id' => $value['id'],
                        'number' => $value['number'],
                        'due_date' => set_date_format($value['date']),
                        'amount_total' => @number_format($value['amount_total'], 2),
                        'amount_total_origin' => $value['amount_total'],
                        'over_due_date' => ($value['date'] < date('Y-m-d')) ? true : false,
                        'status' => $value['state']
                    ];
                    
                    $amountTotal += $value['amount_total'];
                    
                    ($value['date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total'] : '';

                }
                    
            } else {
    
                $data['invoices'][] = [
                    'id' => $value['id'],
                    'number' => $value['number'],
                    'due_date' => set_date_format($value['date']),
                    'amount_total' => @number_format($value['amount_total'], 2),
                    'amount_total_origin' => $value['amount_total'],
                    'over_due_date' => ($value['date'] < date('Y-m-d')) ? true : false,
                    'status' => $value['state']
                ];
                
                $amountTotal += $value['amount_total'];
                
                ($value['date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total'] : '';
            
            }
        }
    }

    $lists = [];
    if (count($ids)) {

        $amountTotal = 0;
        $pastAmountTotal = 0;

        foreach ($data['invoices'] as $key => $value) {

            if (in_array($value['id'], $ids)) {

                $lists[] = $value;
                
                $amountTotal += $value['amount_total_origin'];    
                ($value['due_date'] < date('Y-m-d')) ? $pastAmountTotal += $value['amount_total_origin'] : '';
            }
        }

        $data['invoices'] = $lists;

    }

    $data['amount_total'] = @number_format($amountTotal, 2);
    $data['amount_total_number'] = $amountTotal;
    $data['past_amount_total'] = @number_format($pastAmountTotal, 2);

    return $data;
}