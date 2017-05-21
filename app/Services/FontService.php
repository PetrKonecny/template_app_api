<?php
namespace App\Services;

use App\Font;
use Illuminate\Support\Facades\Storage;

 /**
 * Service providing database access for Font model and creating font files
 */
class FontService {
    
    /** 
    * gets all fonts
    * @return all fonts in the DB
    */
    public function getAll(){
        return Font::all();
    }
   
   /** 
    * finds font by id  
    * @param id - id of searched font
    * @return font or null if none found
    */
    public function findById($id){
        return Font::find($id);
    }
    
    /**
    * gets font file associated with font model
    * @param font - font model to get file for
    * @return file of the font or null 
    */
    public function getFontFile($font){
        return Storage::disk('local')->get('/fonts/'.$font->id.'.'.$font->extension);
    }
    
    /** 
    * creates font file form the input file
    * @param file - file to be created 
    */
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
    
    /** deletes font from the DB
    * @param font - font to be deleted
    */
    public function deleteFont($font){
        $font->delete();
    }
}
