<?php namespace App\Http\Controllers;

use Layout\Layout;
use Layout\Output\FormatHtml as OutputFormatHtml;
use Layout\Output\FormatJson as OutputFormatJson;

class LayoutController extends Controller 
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Layout $layout
     * @param OutputFormatHtml $format
     * @return mixed
     */
    public function html(Layout $layout, OutputFormatHtml $format)
    {
        return $layout->process($format, 'layout_index');
    }

    /**
     * @param Layout $layout
     * @param OutputFormatJson $format
     * @return mixed
     */
    public function json(Layout $layout, OutputFormatJson $format)
    {
        return "<pre>" . json_encode($layout->process($format, 'layout_index'), JSON_PRETTY_PRINT) . "</pre>";
    }

}
