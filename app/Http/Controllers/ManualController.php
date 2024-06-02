<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Manual;
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
    public function readManual($filename){
        $extension = strrchr($filename, '.');
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
        return view('manualReading', ['text'=>$text]);
    }
}

