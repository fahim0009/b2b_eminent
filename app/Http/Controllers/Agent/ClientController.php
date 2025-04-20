<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BusinessPartner;
use App\Models\User;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\CodeMaster;
use App\Models\MofaHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ClientController extends Controller
{
    public function index()
    {
        $data = Client::where('user_id', Auth::user()->id)->orderby('id','DESC')->get();
        $accounts = Account::orderby('id','DESC')->get();
        $bpartners = BusinessPartner::orderby('id','DESC')->get();
        return view('manager.client.index', compact('data','accounts','bpartners'));
    }

    public function getClient(Request $request)
    {

        $id = Auth::user()->id;
        $datas = DB::table('clients')
                ->leftJoin('transactions', 'clients.id', '=', 'transactions.client_id')
                ->select(
                    'clients.id',      // Include only necessary columns
                    'clients.passport_name',
                    'clients.passport_number',
                    'clients.package_cost',   
                    'clients.status',   
                    'clients.mofa',   
                    'clients.mofa_request',   
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.tran_type IN ("package_received", "package_discount") THEN transactions.bdt_amount ELSE 0 END), 0) as total_received'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.tran_type IN ("package_sales", "package_adon") THEN transactions.bdt_amount ELSE 0 END), 0) as total_package')
                )
                ->where('clients.user_id', '=', $id)
                ->groupBy('clients.id', 'clients.passport_name', 'clients.passport_number', 'clients.package_cost', 'clients.status', 'clients.mofa', 'clients.mofa_request') // Group by all selected columns
                ->orderby('id','DESC')
                ->get();

        //  ############################ start visa & service sales calculation  ######################################

        $processing = Client::where('status','1')->where('user_id', $id)->count();
        $decline = Client::where('status','3')->where('user_id', $id)->count();
        $completed = Client::where('status','2')->where('user_id', $id)->count();

        // total package amout with extara charge if added 
        $totalPackageAmount = Transaction::where('user_id', $id)
                            ->whereIn('tran_type', ['package_sales', 'package_adon'])
                            ->sum('bdt_amount');

        // processing package amout with extara charge if added 
        $processingPackageAmount = DB::table('transactions')
            ->join('clients', 'transactions.client_id', '=', 'clients.id')  // Join the tables
            ->where('clients.status', 1)
            ->where('transactions.user_id', $id)
            ->whereIn('transactions.tran_type', ['package_sales', 'package_adon'])
            ->sum('transactions.bdt_amount');

        // completed package amout with extara charge if added 
        $completedPackageAmount = DB::table('transactions')
            ->join('clients', 'transactions.client_id', '=', 'clients.id')  // Join the tables
            ->where('clients.status', 2)
            ->where('transactions.user_id', $id)
            ->whereIn('transactions.tran_type', ['package_sales', 'package_adon'])
            ->sum('transactions.bdt_amount');   

        // package discount on total package cost 
        $totalPkgDiscountAmnt = Transaction::where('user_id',$id)->where('tran_type','package_discount')->sum('bdt_amount');

        //   total package receive amount 
        $totalPackageReceivedAmnt = Transaction::where('user_id',$id)->where('tran_type','package_received')->sum('bdt_amount');

        //  others bill amount like medical contact , embassay extra fees, manpower speed money 
        $totaServiceamt = Transaction::where('user_id',$id)
                        ->whereIn('tran_type', ['service_sales', 'service_adon'])    
                        ->sum('bdt_amount');

        //   total service receive amount 
        $totalServiceReceivedAmnt = Transaction::where('user_id',$id)->where('tran_type','service_received')->sum('bdt_amount');                        
 
        //   receive amount for running work that is not delivered yet including others bill amount      
        $rcvamntForProcessing = ($totalPackageReceivedAmnt +  $totalPkgDiscountAmnt) - ($completedPackageAmount + $totaServiceamt);

        // $directReceivedAmnt = Transaction::where('user_id',$id)->whereNull('client_id')->where('tran_type','Received')->sum('bdt_amount');

        $dueForvisa = (($totalPackageAmount + $totaServiceamt)  - ($totalPackageReceivedAmnt + $totalPkgDiscountAmnt + $totalServiceReceivedAmnt));

        $ttlVisanSrvcRcv = Transaction::where('user_id', $id)
                            ->whereIn('tran_type', ['package_received', 'service_received'])
                            ->sum('bdt_amount');

        $agents = User::where('id',$id)->get();
        $countries = CodeMaster::where('type','COUNTRY')->orderby('id','DESC')->get();
        $accounts = Account::orderby('id','DESC')->get();           
        
        
        $clientTransactions = Transaction::where('user_id',$id)
        ->when($request->from_date, function ($query) use ($request) {
            return $query->where('date', '>=', $request->from_date);
        })
        ->when($request->to_date, function ($query) use ($request) {
            return $query->where('date', '<=', $request->to_date);
        })
        ->when($request->from_date && $request->to_date, function ($query) use ($request) {
            return $query->whereBetween('date', [$request->from_date, $request->to_date]);
        })
        ->orderby('date','DESC')->get();


        return view('manager.client.allclient', compact('datas','agents','countries','accounts','processing','decline','completed','id','completedPackageAmount','processingPackageAmount','totalPackageAmount','ttlVisanSrvcRcv','rcvamntForProcessing','totaServiceamt','totalPkgDiscountAmnt','dueForvisa','clientTransactions'));
    } 


    public function newClient()
    {
        $data = Client::where('status','0')->where('user_id', Auth::user()->id)->orderby('id','ASC')->get();
        $count = $data->count();
        $countries = CodeMaster::where('type','COUNTRY')->orderby('id','DESC')->get();
        $accounts = Account::orderby('id','DESC')->get();
        // $bpartners = BusinessPartner::orderby('id','DESC')->get();
        return view('manager.client.new', compact('data','countries','accounts','count'));
    }


    public function processing()
    {
        $data = Client::where('status','1')->where('user_id', Auth::user()->id)->orderby('id','ASC')->get();
        $count = $data->count();
        $countries = CodeMaster::where('type','COUNTRY')->orderby('id','DESC')->get();
        $accounts = Account::orderby('id','DESC')->get();
        // $bpartners = BusinessPartner::orderby('id','DESC')->get();
        return view('manager.client.processing', compact('data','countries','accounts','count'));
    }

    public function decline()
    {
        $data = Client::where('user_id', Auth::user()->id)->where('status','3')->orderby('id','DESC')->get();
        $agents = User::where('is_type','2')->get();
        $accounts = Account::orderby('id','DESC')->get();
        return view('manager.client.decline', compact('data','accounts'));
    }

    public function completed()
    {
        $data = Client::where('user_id', Auth::user()->id)->where('status','2')->orderby('id','DESC')->get();
        $agents = User::where('is_type','2')->get();
        $accounts = Account::orderby('id','DESC')->get();
        return view('manager.client.completed', compact('data','accounts'));
    }


    public function addClient()
    {
        return view('manager.client.create');
    }

    public function store(Request $request)
    {

        if(empty($request->passport_name)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"Passport Name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }
        

        if(empty($request->passport_number)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"Passport Number \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

        $chkpNumber = Client::where('passport_number',$request->passport_number)->whereNotNull('passport_number')->first();

        if($chkpNumber){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>This Person $chkpNumber->passport_name  ($chkpNumber->passport_number) already added. Check passport number again</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

        if(empty($request->passport_image) || !$request->hasFile('passport_image')){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please upload a valid \"Passport Image\" file..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

        $request->validate([
            'passport_image' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:8048',
        ]);

        

        
        


        $data = new Client;
        $data->user_id = Auth::user()->id;
        $data->passport_number = $request->passport_number;
        $data->passport_name = $request->passport_name;
        $data->passport_rcv_date = $request->passport_rcv_date;
        $data->description = $request->description;
        $data->is_job = $request->is_job;
        $data->status = 0;
        $data->is_ticket = $request->is_ticket;

        // image
        if ($request->passport_image != 'null') {
            $request->validate([
                'passport_image' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf|max:8048',
            ]);
            $rand = mt_rand(100000, 999999);
            $passporImageName = time(). $rand .'.'.$request->passport_image->extension();
            $request->passport_image->move(public_path('images/client/passport'), $passporImageName);
            $data->passport_image = $passporImageName;
        }
        // end

        
        $data->created_by = Auth::user()->id;
        if ($data->save()) {
            $message ="<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Data Create Successfully.</b></div>";
            return response()->json(['status'=> 300,'message'=>$message]);
        }else{
            return response()->json(['status'=> 303,'message'=>'Server Error!!']);
        }
    }

    public function getClientInfo($id)
    {
        $data = Client::where('id',$id)->first();
        $trans = Transaction::where('client_id',$id)->orderby('id','DESC')->get();
        $agents = User::where('is_type','2')->get();
        $countries = CodeMaster::where('type','COUNTRY')->orderby('id','DESC')->get();
        $accounts = Account::orderby('id','DESC')->get();
        return view('manager.client.clientdetail', compact('data','agents','countries','accounts','trans'));
    }

    public function client_image_download($id)
    {

        $client_image = Client::where('id', $id)->first()->client_image;

        $filepath = public_path('images/client/').$client_image;
        if (isset($filepath)) {
            return Response::download($filepath);
        }
        
    }

    public function passport_image_download($id)
    {

        $passport_image = Client::where('id', $id)->first()->passport_image;

        $filepath = public_path('images/client/passport/').$passport_image;
        if (isset($filepath)) {
            return Response::download($filepath);
        }
        
    }

    public function visa_image_download($id)
    {

        $visa = Client::where('id', $id)->first()->visa;

        $filepath = public_path('images/client/visa/').$visa;
        if (isset($filepath)) {
            return Response::download($filepath);
        }
        
    }

    public function manpower_image_download($id)
    {

        $manpower = Client::where('id', $id)->first()->manpower_image;

        $filepath = public_path('images/client/manpower/').$manpower;
        if (isset($filepath)) {
            return Response::download($filepath);
        }
        
    }


    public function createMofaRequest(Request $request)
    {
        
        $data = new MofaHistory();
        $data->client_id = $request->client_id;
        $data->user_id = $request->agentId;
        $data->date = date('Y-m-d');
        $data->note = $request->note;
        if ($data->save()) {
            $client = Client::where('id', $request->client_id)->first();
            $client->mofa_request = 1;
            $client->save();
            $message ="<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Data store Successfully.</b></div>";
            return response()->json(['status'=> 300,'message'=>$message]);
        }else{
            return response()->json(['status'=> 303,'message'=>'Server Error!!']);
        }

    }

    public function getMofaRequest(Request $request)
    {
        $data = MofaHistory::where('client_id',$request->client_id)->where('user_id', Auth::user()->id)->orderby('id', 'ASC')->get();
        
        $prop = '';
        
            foreach ($data as $data){
                
                // <!-- Single Property Start -->
                $prop.= '<tr>
                            <td>
                                '.$data->date.'
                            </td>
                            <td>
                                '.$data->note.'
                            </td>';
                            
                        $prop.= '</tr>';
            }

        return response()->json(['status'=> 300,'data'=>$prop]);
    }


}
