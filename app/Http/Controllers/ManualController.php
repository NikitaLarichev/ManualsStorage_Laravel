<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Manual;
use App\Models\User;
use App\Models\Complaint;
use App\Models\UnconfirmedManual;
use Smalot\PdfParser\Parser;

class ManualController extends Controller
{
    public function index(){
        $manuals = Manual::all();
        return view('manuals_list', ['manuals'=>$manuals]);
    }
    public function search(Request $request){
        $text_search = $request->input('text');
        $manuals = Manual::where('manual_name','like','%'.$text_search.'%')->orWhere('description','like','%'.$text_search.'%')->get();
        return view('manuals_list', ['manuals'=>$manuals]);
    }
    public function deleteManual(Request $request){
        $filename = $request->input('filename');
        if(Storage::disk('confirmed')->exists("$filename")){
           try{
                $uc_manual = Manual::firstWhere('manual_name',"$filename");
                $uc_manual->delete();
           }catch(Exception){
                return redirect('/');
           }
           Storage::delete("confirmed_manuals/$filename");
        }
        return redirect('/');
    }
    public function downloadManual($filename){
       return Storage::download("confirmed_manuals/$filename");
    }
    public function getComplaint(Request $request){
        $validated = $request->validate(['claim'=>'required|max:300']);
        $user_id = $request->input('user_id');
        $author_name = $request->input('author_name');
        $manual_id = $request->input('manual_id');
        $claim = $request->input('claim');
        $complaint = new Complaint();
        $complaint->user_id = $user_id;
        $complaint->manual_id = $manual_id;
        $complaint->claim = $claim;
        $complaint->author_name = $author_name;
        $complaint->save();
        return back();
     }
     public function deleteComplaint(Request $request){
        $complaint_id = $request->input('complaint_id');
        $complaint = Complaint::find($complaint_id);
        $complaint->delete();
        return back();
     }
    public function readManual($filename){
        $extension = strrchr($filename, '.');
        $manual = Manual::firstWhere("manual_name","$filename");
        $complaints = Complaint::where('manual_id', "$manual->id")->get();
        $text = "";
        if ($extension == ".pdf"){
            if(Storage::disk('unconfirmed')->exists("$filename")){
                $path = Storage::disk('unconfirmed')->path($filename);
            } else if(Storage::disk('confirmed')->exists("$filename")){
                $path = Storage::disk('confirmed')->path($filename);
            } else{
                $path = "";
            }
            $pdfParser=new Parser();
            $pdf = $pdfParser->parseFile($path);
            $text = $pdf->getText();
        } else if ($extension == ".txt"){
            if(Storage::disk('unconfirmed')->exists("$filename")){
                $text = Storage::get("unconfirmed_manuals/$filename");
            }else if(Storage::disk('confirmed')->exists("$filename")){
                $text = Storage::get("confirmed_manuals/$filename");
            } else {}
        } else if ($extension == ".doc"||$extension == ".docx"){
            if(Storage::disk('unconfirmed')->exists("$filename")){
                $text = Storage::get("unconfirmed_manuals/$filename");
            }
            else if(Storage::disk('confirmed')->exists("$filename")){
                $text = Storage::get("confirmed_manuals/$filename");
            }
        } else {}
        return view('manualReading', ['text'=>$text, 'manual_id'=>$manual->id, 'complaints'=>$complaints]);
    }
}

