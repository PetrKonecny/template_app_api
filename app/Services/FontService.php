<?php
namespace App\Services;

use App\Font;
use Illuminate\Support\Facades\Storage;

 /* Description of FontService
 *
 * @author Petr2
 */
class FontService {
    
    public function getAll(){
        return Font::all();
    }
   
    public function findById($id){
        return Font::find($id);
    }
    
    public function getFontFile($font){
        return Storage::disk('local')->get('/fonts/'.$font->id.'.'.$font->extension);
    }
    
    public function createFont($file){
        $destinationPath = storage_path().'/app/fonts';
        $extension = $file->getClientOriginalExtension();
        $font = new Font;
        $font->name = $file->getClientOriginalName();
        $font->extension = $extension;
        $font->save();
        $fileName = $font->id.'.'.$extension;
        $file->move($destinationPath, $fileName);
    }
    
    public function deleteFont($font){
        $font->delete();
    }
}
