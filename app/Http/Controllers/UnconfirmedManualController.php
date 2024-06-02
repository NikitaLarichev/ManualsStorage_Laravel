<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Models\UnconfirmedManual;
use App\Models\Manual;
use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;

class UnconfirmedManualController extends Controller
{
    public function confirmManual(Request $request){
        $validated = $request->validate(['filename'=>'required|max:300', 'description'=>'required|max:500']);
        $filename = $request->input('filename');
        $description = $request->input('description');
        if(Storage::disk('unconfirmed')->exists("$filename")){            
            $uc_manual = UnconfirmedManual::firstWhere('manual_name',"$filename");
            $c_manual = new Manual();
            $c_manual->manual_name = $uc_manual->manual_name;
            $c_manual->description = $description;
            $c_manual->author_email = $uc_manual->author_email;
            DB::beginTransaction();
            try{
                $c_manual->save();
                $uc_manual->delete();
            }catch(Exception){
                DB::rollBack();
            }finally{
                DB::commit();
            }         
            Storage::move("unconfirmed_manuals/$filename", "confirmed_manuals/$filename");            
        }
        return back();
    }
    public function deleteManual(Request $request){
        $filename = $request->input('filename');
        if(Storage::disk('unconfirmed')->exists("$filename")){
           try{
                $uc_manual = UnconfirmedManual::firstWhere('manual_name',"$filename");
                $uc_manual->delete();
           }catch(Exception){
                return redirect('/');
           }
           Storage::delete("unconfirmed_manuals/$filename");
        }
        return back();
    }

    public function manualLoading(){
        return view('manual_loading');
    }

    public function getUnconfirmedManualsList(){
        $uc_manuals = UnconfirmedManual::all();
        return view('unconfirmed_manuals_list', ['uc_manuals'=> $uc_manuals]);
    }

    public function update(Request $request){
        $validated = $request->validate(['file'=>'file|mimetypes:application/pdf,text/plain,application/octet-stream,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'description'=>'required|max:500']);
        $file = $request->file('file');
        $ty = $file->getMimeType();
        //return "$ty";
        $description = $request->input('description');
        $author_email = $request->input('author_email');
        $name = $file->getClientOriginalName();
        $lastDotPos = strrpos($name, '.');
        //$extension = $file->extension();
        //$extension = '.'.$extension;
        $extension = strrchr($name,'.');
        $onlyName = substr($name, 0, $lastDotPos);
        $newName = $onlyName."_".time().$extension;
        $file->storeAs('unconfirmed_manuals', $newName);
        $newManual = new UnconfirmedManual();
        $newManual->manual_name = $newName;
        $newManual->description = $description;
        $newManual->author_email = $author_email;
        $newManual->save();
        return back();
    }
}
