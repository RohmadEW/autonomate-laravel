<?php

/*
 * PENDATAAN MAARIF NU
 * Dikembangkan oleh Rohmad Eko Wahyudi
 * rohmad.ew@gmail.com
 */

namespace App\Libraries;

/**
 * Description of CartHandler
 *
 * @author Rohmad Eko Wahyudi
 */
class CartHandler {

    //put your code here

    public function chart($id_chart, $title, $single) {
        $this->content_chart($id_chart, $title, $single);
    }

    private function content_chart($id_chart, $title, $single = FALSE) {
        echo '<div class="content animate-panel panel-' . $id_chart . '" style="margin-top: -60px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="hpanel hblue">
                        <div class="panel-heading hbuilt">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <!--<button onclick="reload_chart_' . $id_chart . '(chart_' . $id_chart . ');" class="btn btn-success btn-xs">Reload</button>-->
                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Rubah grafik ke <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'spline\')' : 'chart_transform_spline(chart_' . $id_chart . ')') . ';">Grafik Spline</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'line\')' : 'chart_transform_line(chart_' . $id_chart . ')') . ';">Grafik Line</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'area\')' : 'chart_transform_area(chart_' . $id_chart . ')') . ';">Grafik Area</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'area-spline\')' : 'chart_transform_area_spline(chart_' . $id_chart . ')') . ';">Grafik Area Spline</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'bar\')' : 'chart_transform_bar(chart_' . $id_chart . ')') . ';">Grafik Bar</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'pie\')' : 'chart_transform_pie(chart_' . $id_chart . ')') . ';">Grafik Pie</a></li>
                                        <li><a onclick="' . ($single ? 'request_chart_' . $id_chart . '(\'donut\')' : 'chart_transform_donut(chart_' . $id_chart . ')') . ';">Grafik Donut</a></li>
                                    </ul>
                                </div>
                            </div>
                            ' . $title . '
                        </div>
                        <div class="panel-body">
                                <div>
                                    <div id="' . $id_chart . '"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

}
