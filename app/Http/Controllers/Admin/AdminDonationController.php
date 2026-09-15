<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;

class AdminDonationController extends Controller
{
    public function index()
    {
        $donations = Donation::orderBy('id','desc')->get();
        $total_completed = Donation::where('status','Completed')->sum('amount');
        return view('admin.donation.index',compact('donations','total_completed'));
    }

    public function delete($id)
    {
        Donation::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Donation deleted successfully.');
    }

    public function export()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=donations_'.date('Y-m-d_H-i-s').'.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['SL', 'Donor Name', 'Email', 'Amount', 'Currency', 'Method', 'Transaction ID', 'Status', 'Date']);

        $donations = Donation::orderBy('id', 'ASC')->get();

        $i = 0;
        foreach ($donations as $row) {
            $i++;
            fputcsv($output, [
                $i,
                $row->donor_name,
                $row->donor_email,
                $row->amount,
                $row->currency,
                $row->payment_method,
                $row->transaction_id,
                $row->status,
                $row->created_at,
            ]);
        }

        fclose($output);
        exit;
    }
}
