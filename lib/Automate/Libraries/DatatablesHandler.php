<?php

/*
 * PENDATAAN MAARIF NU
 * Dikembangkan oleh Rohmad Eko Wahyudi
 * rohmad.ew@gmail.com
 */

namespace App\Libraries;

class DatatablesHandler {
    /*
     * if ($request->ajax()) {
      $registrants = Registrant::with('users', 'sub_districts.districts');
      return Datatables::of($registrants)
      ->addColumn('validate', function($registrants) {
      return sprintf('<a href="javascript:void();" onclick="change_validate(%s)"><i class="fa fa-%s"></i></a>', $registrants->id, $registrants->validasi ? 'close' : 'check');
      })
      ->addColumn('alamat_lengkap', function($registrants) {
      return $registrants->alamat
      . ', Kec. ' . $registrants->sub_districts['name']
      . ', ' . $registrants->sub_districts['districts']['name'];
      })
      ->rawColumns(['validate', 'alamat_lengkap'])
      ->make(true);
      }

      $datatables = array(
      'columns' => [
      ['data' => 'users.email', 'name' => 'users.email', 'title' => 'Email', 'footer' => 'Email'],
      ['data' => 'users.name', 'name' => 'users.name', 'title' => 'Nama', 'footer' => 'Nama'],
      ['data' => 'alamat_lengkap', 'name' => 'alamat_lengkap', 'title' => 'Alamat', 'footer' => 'Alamat'],
      ['data' => 'hp', 'name' => 'hp', 'title' => 'HP', 'footer' => 'HP'],
      ['data' => 'validate', 'name' => 'validasi', 'title' => 'Validasi', 'footer' => 'Validasi'],
      ],
      'searching' => [
      'validate' => [
      'select' => array(
      '' => '-- Pilih --',
      '0' => 'Belum',
      '1' => 'Sudah'
      )
      ]
      ]
      );
      $html = $htmlBuilder->columns($datatables['columns']);
      $html->ajax(DatatablesHandler::defaultAjax(route('registrants.datatables')));
      $html->parameters(DatatablesHandler::defaultParameters($datatables));

      return view('backend.admin.registrant.index', compact('html'));
     * 
     *      */

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
                        $column_search .= "if(title.toUpperCase() === '" . strtoupper($columns['title']) . "') input = '<input type=\"text\" placeholder=\"Cari ' + title + '\" class=\"form-control input-sm datatables-search datatables-search-' + title.replace(\" \", \"-\") + '\" style=\"width:100%\">';";
                    }
                }
            }
        } else {
            $column_search = "if(title.toUpperCase() === 'AKSI') input = '<input type=\"text\" placeholder=\"Cari ' + title + '\" class=\"form-control input-sm datatables-search datatables-search-' + title.replace(\" \", \"-\") + '\" style=\"width:100%\">';";
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
