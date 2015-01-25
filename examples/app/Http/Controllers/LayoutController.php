<?php namespace App\Http\Controllers;

use Layout\LayoutConfig;
use Layout\Processor\ProcessorHtml;
use Layout\Processor\ProcessorJson;


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
     * @param LayoutConfig $config
     * @param ProcessorHtml $processor
     * @return mixed
     * @throws \Layout\Processor\ProcessorException
     */
    public function html(LayoutConfig $config, ProcessorHtml $processor)
    {
        $config->load('layout_index');

        return $processor->run($config);
    }

    /**
     * @param LayoutConfig $config
     * @param ProcessorJson $processor
     * @return mixed
     * @throws \Layout\Processor\ProcessorException
     */
    public function json(LayoutConfig $config, ProcessorJson $processor)
    {
        $config->load('layout_index');

        return $processor->run($config);
    }

}
