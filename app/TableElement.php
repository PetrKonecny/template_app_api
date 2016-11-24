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
        $content_rows = json_decode($this->content->rows);
        $total_width = 0;
        foreach($rows[0]->cells as $cell){
            $total_width += $cell->width;
        }
        $string = "<table style='position: absolute; table-layout: fixed; border: 1px solid black; border-collapse: collapse; width:".$total_width."px; left:".$this->positionX."px; top:".$this->positionY."px;'><tbody>";
        foreach($rows as $y=>$row){
            $string .= "<tr style=' height:".$row->height."px;'>";
            foreach($row->cells as $x=>$cell){
                $text ="";
                if(!is_array($content_rows[$y]->cells[$x])){
                    $text = $content_rows[$y]->cells[$x]->text;
                }
                $string .= "<td style='border: 1px solid black; border-collapse: collapse; width:".$cell->width."px; height:".$row->height."px;'>".$text."</td>";
            }
            $string .= "</tr>";
        }
        $string .= "</tbody></table>";
        return $string;
    }

}

?>