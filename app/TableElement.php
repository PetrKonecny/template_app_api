<?php

namespace App;
use App\Element;
use App\Font;
use App\TableContent;
class TableElement extends Element {

    protected static $singleTableType = 'table_element';
    
    protected $fillable = ['rows','positionX','positionY'];
    
    public function toHtml($instanceId){
        $rows = json_decode($this->rows);
        $content_rows = json_decode($this->contentsForInstance($instanceId)->first()->rows);
        $string = $this->initFonts($rows);
        $total_width = 0;
        foreach($rows[0]->cells as $cell){
            $total_width += $cell->width;
        }
        $string .= "<table style='position: absolute; table-layout: fixed; border: 1px solid black; border-collapse: collapse; width:".$total_width."px; left:".$this->positionX."px; top:".$this->positionY."px;'><tbody>";
        foreach($rows as $y=>$row){
            $string .= "<tr style=' height:".$row->height."px;'>";
            foreach($row->cells as $x=>$cell){
                $text ="";
                if(!is_array($content_rows[$y]->cells[$x])){
                    $text = $content_rows[$y]->cells[$x]->text;
                }
                $font = "";
                $align = "";
                $vertical = "";
                $fontSize ="";
                if(property_exists($cell, "font") && $cell->font != null){
                    $font = "font-family: font".$cell->font->id."; ";
                    $fontSize = "font-size: ".$cell->font_size."px; ";
                }               
                if(property_exists($cell, "text_align")){
                    $align = "text-align: ".$cell->text_align."; ";
                }
                if(property_exists($cell, "vertical_align")){
                    $vertical = "vertical-align: ".$cell->vertical_align."; ";
                }
                $string .= "<td style='border: 1px solid black; border-collapse: collapse; width:".$cell->width."px; height:".$row->height."px; ".$font.$fontSize.$align.$vertical."'>".$text."</td>";
            }
            $string .= "</tr>";
        }
        $string .= "</tbody></table>";
        return $string;
    }
    
    private function initFonts($rows){
        $fonts = [];
        $string = "";
        foreach($rows as $row){
            foreach($row->cells as $cell){
                if(property_exists($cell,"font")){
                    array_push($fonts,$cell->font);
                }
            }
        }
        
        $fonts = array_unique($fonts, SORT_REGULAR);
        foreach($fonts as $font){
            $string .= "<style>";
                $string .= "@font-face {";
                $string .= " font-family: '" ."font" . $font->id . "';";
                $string .= " src: url('"."http://localhost:8080/font/".$font->id ."/file" ."') format('truetype');";
                $string .= "}";
            $string .= "</style>";
        }
        return $string;
    }
}

?>