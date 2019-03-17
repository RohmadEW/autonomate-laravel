<?php

/*
 * PENDATAAN MAARIF NU
 * Dikembangkan oleh Rohmad Eko Wahyudi
 * rohmad.ew@gmail.com
 */

namespace Automate\Libraries;

class DatatablesHandler {
    
    public static function defaultAjax($route, $params = array()) {
        $paramsDefault = [
            'url' => $route,
            'type' => 'POST',
            'headers' => array(
                'X-CSRF-TOKEN' => "$('meta[name=\"csrf-token\"]').attr('content')"
            )
        ];

        if (is_array($params)) {
            array_merge($paramsDefault, $params);
        }

        return $paramsDefault;
    }

    public static function defaultParameters($params = array()) {
        if (isset($params['columns'])) {
            $column_search = '';
            foreach ($params['columns'] as $index => $columns) {
                if (!isset($columns['searchable']) || (isset($columns['searchable']) && $columns['searchable'])) {
                    if (isset($params['searching'][$columns['data']]['select'])) {
                        $option_select = "<option value=\"\">-- Pilih --</option>";
                        foreach ($params['searching'][$columns['data']]['select'] as $value => $text) {
                            $option_select .= "<option value=\"" . $value . "\">" . $text . "</option>";
                        }

                        $column_search .= "if(title.toUpperCase() === '" . strtoupper($columns['title']) . "') input = '<select class=\"form-control input-sm datatables-search datatables-search-' + title.replace(\" \", \"-\") + '\" style=\"width:100%\">" . $option_select . "</select>';";
                    } else {
                        if (strtoupper($columns['title']) !== 'AKSI')
                            $column_search .= "if(title.toUpperCase() === '" . strtoupper($columns['title']) . "') input = '<input type=\"text\" placeholder=\"Cari ' + title + '\" class=\"form-control input-sm datatables-search datatables-search-' + title.replace(\" \", \"-\") + '\" style=\"width:100%\">';";
                    }
                }
            }
        } else {
            $column_search = "if(title.toUpperCase() !== 'AKSI') input = '<input type=\"text\" placeholder=\"Cari ' + title + '\" class=\"form-control input-sm datatables-search datatables-search-' + title.replace(\" \", \"-\") + '\" style=\"width:100%\">';";
        }

        $initComplete = "
            function(){
                $('.table-tooltip').tooltip();
                this.api().columns().every(function () {
                    var that = this;
                    var title = $(this.footer()).text();
                    var input = title;
                    " . $column_search . "  
                    var temp_timeout = null;
                    $(input).appendTo($(this.footer()).empty()).on('change', function () {
                        that.search(this.value).draw();
                    });
                });
                " . (isset($params['initComplete']) ? $params['initComplete'] : '') . "
            }";

        unset($params['initComplete']);

        $paramsDefault = [
            'bDestroy' => 'bDestroy',
            'dom' => "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 text-right'f>>rt<'row'<'col-sm-4'i><'col-sm-8 text-right'p>>",
            'lengthMenu' => array(
                array(10, 25, 50, 100, -1),
                array(10, 25, 50, 100, "All"),
            ),
            'initComplete' => $initComplete
        ];

        if (is_array($params)) {
            array_merge($paramsDefault, $params);
        }

        $paramsDefault['buttons'][] = array(
            'extend' => 'excel',
            'text' => 'Excel',
            'className' => 'btn btn-sm btn-default btn-hover-info',
            'title' => 'DownloadDataXLSX'
        );
        $paramsDefault['buttons'][] = array(
            'extend' => 'csv',
            'text' => 'CSV',
            'className' => 'btn btn-sm btn-default btn-hover-info',
            'title' => 'DownloadDataCSV'
        );
        $paramsDefault['buttons'][] = array(
            'extend' => 'pdf',
            'text' => 'PDF',
            'className' => 'btn btn-sm btn-default btn-hover-info',
            'title' => 'DownloadDataPDF'
        );
        $paramsDefault['buttons'][] = array(
            'extend' => 'print',
            'text' => 'Print',
            'className' => 'btn btn-sm btn-default btn-hover-info',
            'title' => 'DownloadDataPDF'
        );
        $paramsDefault['buttons'][] = array(
            'text' => 'Reload',
            'className' => 'btn btn-sm btn-default btn-hover-info',
            'action' => 'function (e, dt, node, config) {
                    dt.ajax.reload();
                }'
        );

        if (isset($params['buttons'])) {
            foreach ($params['buttons'] as $key => $detail) {
                if ($key == 'add') {
                    $paramsDefault['buttons'][] = array(
                        'text' => 'Tambah',
                        'className' => 'btn btn-sm btn-default btn-hover-primary',
                        'action' => 'function (e, dt, node, config) {
                                    fnDatatablesAdd();
                                }'
                    );
                } else {
                    $paramsDefault['buttons'][] = array(
                        'text' => $detail['text'],
                        'className' => 'btn btn-sm btn-default btn-hover-primary',
                        'action' => 'function (e, dt, node, config) {
                                    ' . $detail['action'] . '
                                }'
                    );
                }
            }
        }

        return $paramsDefault;
    }

}
