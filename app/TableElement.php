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
        $total_width = 0;
        foreach($rows[0]->cells as $cell){
            $total_width += $cell->width;
        }
        $string = "";
        $string .= "<table style='position: absolute; table-layout: fixed; border: 1px solid black; border-collapse: collapse; width:".$total_width."px; left:".$this->positionX."px; top:".$this->positionY."px;' z-index: ".$this->positionZ.";><tbody>";
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
                $cellWidth ="";
                $cellBackgroundColor="";
                $cellTextColor="";
                $cellBorderColor="";
                $cellColSpan="";
                $cellRowSpan="";
                $cellBorderWidth="";
                $cellBorderStyle="";
                if(property_exists($cell, "font") && $cell->font != null){
                    if(property_exists($cell->font, 'id')){
                        $font = "font-family: font".$cell->font->id."; ";
                    }else{
                        $font = "font-family: ".$cell->font->name."; ";
                    }                  
                }     
                if(property_exists($cell, "font_size")){
                    $fontSize = "font-size: ".$cell->font_size."px; ";
                }
                if(property_exists($cell, "text_align")){
                    $align = "text-align: ".$cell->text_align."; ";
                }
                if(property_exists($cell, "vertical_align")){
                    $vertical = "vertical-align: ".$cell->vertical_align."; ";
                }
                if(property_exists($cell, "width")){
                    $cellWidth = "width: ".$cell->width."px; ";
                }
                if(property_exists($cell, "background_color")){
                    $cellBackgroundColor = "background-color: ".$cell->background_color."; ";
                }
                if(property_exists($cell, "text_color")){
                    $cellTextColor = "color: ".$cell->text_color."; ";
                }
                if(property_exists($cell, "border_color")){
                    $cellBorderColor = "border-color: ".$cell->border_color."; ";
                }
                if(property_exists($cell, "colspan")){
                    $cellColSpan = "colspan = ".$cell->colspan." ";
                }
                if(property_exists($cell, "rowspan")){
                    $cellRowSpan = "rowspan = ".$cell->rowspan." ";
                }
                if(property_exists($cell, "border_width")){
                    $cellBorderWidth = "border-width: ".$cell->border_width."px ; ";
                }
                if(property_exists($cell, "border_style")){
                    $cellBorderStyle = "border-style: ".$cell->border_style."; ";
                }
   
                $string .= "<td ".$cellColSpan.$cellRowSpan."style='border: 1px solid black; border-collapse: collapse; height:".$row->height."px; ".$cellWidth.$font.$fontSize.$align.$vertical.$cellBackgroundColor.$cellBorderColor.$cellTextColor.$cellBorderWidth.$cellBorderStyle."'>".$text."</td>";
            }
            $string .= "</tr>";
        }
        $string .= "</tbody></table>";
        return $string;
    }
}

?>