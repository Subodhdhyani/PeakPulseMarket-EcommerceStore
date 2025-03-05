<?php

namespace App\Http\Controllers;

use App\Models\tblbooking;
use App\Models\tblcart;
use App\Models\tbltracking;
use App\Models\tblcategorie;
use App\Models\tblreview;
use App\Models\tbluser;
use App\Models\tblorderhelp;
use App\Models\tblproduct;
use App\Models\tblreturntracking;
use Illuminate\Support\Facades\DB;


class graphcontroller extends Controller
{
    //Function Related to Show Graphs on Admin Panel
    public function graph_dashboard()
    {
        return view('admin.graphs.graph_dashboard');
    }
    public function productGraph()
    {
        $totalpulse = tblproduct::where('category_name', 'pulse')->where('quantity', '>', 0)->count();
        $totalspice = tblproduct::where('category_name', 'spice')->where('quantity', '>', 0)->count();
        $totalsnack = tblproduct::where('category_name', 'snack')->where('quantity', '>', 0)->count();
        $totalpickle = tblproduct::where('category_name', 'pickle')->where('quantity', '>', 0)->count();

        // Fetch out-of-stock products
        $outOfStockPulse = tblproduct::where('category_name', 'pulse')->where('quantity', '<=', 0)->count();
        $outOfStockSpice = tblproduct::where('category_name', 'spice')->where('quantity', '<=', 0)->count();
        $outOfStockSnack = tblproduct::where('category_name', 'snack')->where('quantity', '<=', 0)->count();
        $outOfStockPickle = tblproduct::where('category_name', 'pickle')->where('quantity', '<=', 0)->count();

        // Pass the counts to the view
        return view('admin.graphs.product-graph', compact(
            'totalpulse',
            'totalspice',
            'totalsnack',
            'totalpickle',
            'outOfStockPulse',
            'outOfStockSpice',
            'outOfStockSnack',
            'outOfStockPickle'
        ));
    }

    public function userGraph()
    {
        //total active user i.e unblocked user
        $totalactiveuser = tbluser::where('status', 'unblock')
            ->where('role', 'user')
            ->count();
        //total inactive user i.e blocked user
        $totalinactiveuser = tbluser::where('status', 'block')
            ->where('role', 'user')
            ->count();
        //total admin that manages portal                         
        $totaladmin = tbluser::where('status', 'unblock')
            ->where('role', 'admin')
            ->count();
        // Pass the counts to the view
        return view('admin.graphs.user-graph', compact('totalactiveuser', 'totalinactiveuser', 'totaladmin'));
    }
    public function bookingGraph()
    {
        //count all new booking received
        $totalnewbooking = tblbooking::where('payment_status', 'Successful')
            ->where('order_status', '0')
            ->count();
        // Count all records where order_status = 3 (Delivered)
        $deliveredCount = tblbooking::where('order_status', '3')
            ->where('payment_status', 'Successful')
            ->count();

        // Count all records where order_status = 5 (Refunded)
        $refundedCount = tblbooking::where('order_status', '5')
            ->where('payment_status', 'Refunded')
            ->count();

        // Count all records where payment_status = 'pending' (Failed payments)
        $failedPaymentCount = tblbooking::where('payment_status', 'pending')
            ->where('order_status', '0')
            ->count();

        //count total sale when order delivered successfull
        $deliveredTotalAmount = tblbooking::where('order_status', '3')
            ->where('payment_status', 'Successful')
            ->sum('total_amount_paid');

        // Get the mostly sold product (tblproduct_id) from successful deliveries
        $mostSoldProduct = tblbooking::where('order_status', '3')
            ->where('payment_status', 'Successful')
            ->select('tblproduct_id', DB::raw('COUNT(tblproduct_id) as count'))
            ->groupBy('tblproduct_id')
            ->orderByDesc('count')
            ->first();
        // Pass the counts to the view
        return view('admin.graphs.booking-graph', compact('totalnewbooking', 'deliveredCount', 'refundedCount', 'failedPaymentCount', 'deliveredTotalAmount', 'mostSoldProduct'));
    }

    public function helpGraph()
    {
        // fetch all records grouped by booking_id
        $groupedRecords = tblorderhelp::select('booking_id', 'order_help_status', 'created_at', 'id')
            ->orderBy('booking_id')
            ->orderBy('created_at')
            ->get()
            ->groupBy('booking_id');

        $newhelp = 0;
        $pendinghelp = 0;
        $resolvedhelp = tblorderhelp::where('order_help_status', '2')->count(); // Count all status = 2 i.e total complains resolved till

        foreach ($groupedRecords as $booking_id => $records) {
            $latest0 = $records->where('order_help_status', '0')->last(); // when new help/complain raise
            $latest1 = $records->where('order_help_status', '1')->last(); // help/complain pending
            $latest2 = $records->where('order_help_status', '2')->last(); // help/complain resolved
            // resolve counts based on `created_at` priority
            if ($latest2) {
                // If status = 2 exists, exclude 0 and 1 if their created_at is less than the latest 2
                if ($latest0 && $latest0->created_at > $latest2->created_at) {
                    $newhelp++;
                }
                if ($latest1 && $latest1->created_at > $latest2->created_at) {
                    $pendinghelp++;
                }
            } else if ($latest1) {
                // If status = 1 exists, exclude 0 if its created_at is less than the latest 1
                if ($latest0 && $latest0->created_at > $latest1->created_at) {
                    $newhelp++;
                }
                $pendinghelp++;
            } else if ($latest0) {
                // If only status = 0 exists, count it
                $newhelp++;
            }
        }

        return view('admin.graphs.help-graph', compact('newhelp', 'pendinghelp', 'resolvedhelp'));
    }

    public function trackGraph()
    {
        $columns = [
            'order_status_0_to_1' => 'Preparing',
            'order_status_0_to_4' => 'Cancel before Prepared',
            'order_status_1_to_2' => 'Dispatched',
            'order_status_1_to_4' => 'Cancel after Prepared',
            'order_status_2_to_3' => 'Delivered',
            'order_status_3_to_6' => 'Return Request',
            'order_status_6_to_4' => 'Return received now Refunding',
            'order_status_4_to_5' => 'Refunded',
        ];

        // Get the keys of the $columns array (numerically indexed)
        $columnKeys = array_keys($columns);
        $query = DB::table('tbltrackings');
        $selectRaw = [];

        foreach ($columnKeys as $key => $column) {
            if ($key === 0) {
                // Handle the first column
                $subsequentColumns = array_slice($columnKeys, $key + 1);
                $subsequentConditions = !empty($subsequentColumns)
                    ? implode(' AND ', array_map(fn($col) => "`$col` IS NULL", $subsequentColumns))
                    : ''; // If no subsequent columns, leave condition empty

                $selectRaw[] = "COUNT(CASE WHEN `$column` IS NOT NULL" .
                    ($subsequentConditions ? " AND $subsequentConditions" : '') .
                    " THEN 1 END) as count_$column";
            } else {
                // Handle other columns
                $subsequentColumns = array_slice($columnKeys, $key + 1);
                $subsequentConditions = !empty($subsequentColumns)
                    ? implode(' AND ', array_map(fn($col) => "`$col` IS NULL", $subsequentColumns))
                    : ''; // If no subsequent columns, leave condition empty

                $previousColumn = $columnKeys[$key - 1];
                $currentColumn = $column;
                $selectRaw[] = "COUNT(CASE WHEN 
                                                (`$previousColumn` IS NOT NULL OR `$previousColumn` IS NULL) 
                                                AND `$currentColumn` IS NOT NULL" .
                    ($subsequentConditions ? " AND $subsequentConditions" : '') .
                    " THEN 1 END) as count_$column";
            }
        }

        // get the counts
        $counts = $query->selectRaw(implode(', ', $selectRaw))->first();
        // Format the labels (map the column name to label name text)
        $formattedLabels = array_map(function ($column) use ($columns) {
            // Get label name from mapping
            return $columns[$column] ?? strtoupper(str_replace(['count_', '_'], ['', ' '], $column));
        }, $columnKeys);

        // Pass the counts and formatted labels to the view
        return view('admin.graphs.track-graph', [
            'counts' => (array) $counts,
            'formattedLabels' => $formattedLabels
        ]);
    }

    public function returnGraph()
    {
        // Get all booking_ids from tblreturntracking where return_status is 0 or 1
        $excludedBookingIds = tblreturntracking::whereIn('return_status', ['0', '1'])
            ->pluck('booking_id')
            ->toArray();
        // Total return requests, excluding those with booking_ids in excludedBookingIds
        $totalreturnrequest = tbltracking::whereNotNull('order_status_3_to_6')
            ->whereNotIn('booking_id', $excludedBookingIds)
            ->count();
        // Total return generated
        $totalreturngenerated = tblreturntracking::whereNotNull('booking_id')
            ->where('return_status', '0')
            ->count();
        // Total return received
        $totalreturnreceived = tblreturntracking::whereNotNull('booking_id')
            ->where('return_status', '1')
            ->count();
        // Pass the counts to the view
        return view('admin.graphs.return-graph', compact('totalreturnrequest', 'totalreturngenerated', 'totalreturnreceived'));
    }

    public function otherGraph()
    {
        //total number of listed category
        $totalcategory = tblcategorie::count();
        //total no of items in user cart which is not buy till now
        $total_pending_items_in_carts = tblcart::count();
        //total review submit by user and not accept by admin to show on product reveiew page
        $latestReviews = tblreview::selectRaw('booking_id, MAX(created_at) as max_created_at, MAX(id) as id')
            ->where('status', '0')
            ->groupBy('booking_id');

        // Exclude booking_ids where a record with status = 1 and later created_at exists
        $excludedBookingIds = tblreview::where('status', '1')
            ->whereIn('booking_id', $latestReviews->pluck('booking_id'))
            ->where(function ($query) use ($latestReviews) {
                foreach ($latestReviews->get() as $review) {
                    $query->orWhere(function ($subQuery) use ($review) {
                        $subQuery->where('booking_id', $review->booking_id)
                            ->where('created_at', '>', $review->max_created_at);
                    });
                }
            })
            ->pluck('booking_id');

        $totalreviewpending = tblreview::where('status', '0')
            ->whereIn('id', $latestReviews->pluck('id'))
            ->whereNotIn('booking_id', $excludedBookingIds)
            ->count();
        return view('admin.graphs.other-graph', compact('totalcategory', 'total_pending_items_in_carts', 'totalreviewpending'));
    }

    //End of class
}
