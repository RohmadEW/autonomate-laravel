<?php

/*
 * PENDATAAN MAARIF NU
 * Dikembangkan oleh Rohmad Eko Wahyudi
 * rohmad.ew@gmail.com
 */

namespace Automate\Libraries;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

/**
 * Description of SavingExcel
 *
 * @author Rohmad Eko Wahyudi
 */
class ExcelHandler {

    var $sheetData;
    var $row;

    public function __construct($sheetData, $row) {
        $this->sheetData = $sheetData;
        $this->row = $row;
    }

    public function getData($addRowOrColumn, $column = null, $columnLast = null) {
        if ($column == null)
            $column = $addRowOrColumn;
        else
            $this->row += $addRowOrColumn;

        if ($columnLast == null) {
            return $this->sheetData[$this->row][$column];
        } else {
            return $this->getMultipleColumn($column, $columnLast);
        }
    }

    public function getDataRelationship($table, $addRowOrColumn, $column = null) {
        $value = $this->getData($addRowOrColumn, $column);
        if ($value == "" || $value == null || $value == "-")
            return null;

        $result = DB::table($table)->where([
                    ['kode', $value],
                    ['ta_semester_id', Session::get('ta_semester_id')]
                ])->first();

        if (isset($result->id))
            return $result->id;
        else
            return null;
    }

    public function setRow($row) {
        $this->row = $row;
    }

    public function addRow($countAdd) {
        $this->row += $countAdd;
    }

    private function getMultipleColumn($columnFirst, $columnLast) {
        $value = "";
        $looping = true;
        $column = $columnFirst;
        $columnLast++;

        while ($looping) {
            $value .= $this->sheetData[$this->row][$column];
            $column++;

            if ($column == $columnLast)
                $looping = false;
        }

        return $value;
    }

    public function shiftAlphabet($kolom, $count) {
        for ($i = 0; $i < $count; $i++) {
            $kolom++;
        }

        return $kolom;
    }

}
