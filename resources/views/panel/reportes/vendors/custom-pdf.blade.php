<!DOCTYPE html>
<html>
    <body>
        <table>
            <thead>
                <tr class="mainhead">
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #69F6E7;text-align: center" colspan="18">{{$data[0]->name}} - {{__('sidebar.reportVendors')}} {{date('Y')}} / {{date('M')}} / {{date('D')}} </th>
                </tr>
                <tr class="mainhead">
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;">Tracking number </th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Pick up Date </th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Vendor order  reference </th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Customer Name</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Mobile </th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >City</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 400px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Address</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Pick up timing</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Delivered time</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Payment Method</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Total Amount</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >COD</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >N of Boxes</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Driver Name</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Vehicle Type</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Status</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Completion Date</th>
                    <th style="border: 1px solid #000; font-size: 16px ;width: 200px; background-color: #2FB6EC;text-align: center; font-weight: bold;" >Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data[0]->orders as $order)
                    <tr>
                        <td style="border: 1px solid #000; font-size: 14px ;text-align: center">  {{$order->id}} </td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->pick_up ? \Carbon\Carbon::parse($order->pick_up)->format('Y-m-d') : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->order_reference ? $order->order_reference : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->name}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->phone ? $order->phone : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->city()->exists() ? $order->city->name : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">Street: {{$order->street ? $order->street : '-'}},Block: {{$order->block ? $order->block : '-'}},Home: {{$order->home ? $order->home : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->pick_up ? \Carbon\Carbon::parse($order->pick_up)->format('H:s:i') : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->date_to_driver ? \Carbon\Carbon::parse($order->date_to_driver)->format('H:s:i') : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center; background-color: #FFED33">{{$order->payment_method ? 'Cash' : 'Online'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center; background-color: #FFED33">{{$order->totale_amount ? $order->totale_amount : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center; background-color: #FFED33">{{$order->payment_method ? $order->totale_amount : 0}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->items ? $order->items : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->delivery()->exists() ? $order->delivery->name : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->delivery()->exists() && $order->delivery->methodShipping()->exists() ? $order->delivery->methodShipping->name : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->StatusApi()->exists() ? $order->StatusApi->name : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->date_to_driver ? \Carbon\Carbon::parse($order->date_to_driver)->format('Y-m-d') : '-'}}</td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center">{{$order->description ? $order->description : '-'}}</td>
                    </tr>
                @endforeach
                    <tr>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border: 1px solid #000; font-size: 16px ;text-align: center; font-weight: bold;">Total</td>
                        <td></td>
                        <td style="border: 1px solid #000; font-size: 12px ;text-align: center; font-weight: bold;">{{$data[0]->orders_sum_totale_amount ? $data[0]->orders_sum_totale_amount : '0' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
    </body>
</html>

