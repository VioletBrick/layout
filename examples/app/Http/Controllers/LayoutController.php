<?php namespace App\Http\Controllers;

use Layout\Layout;

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
     * Render layout example page.
     *
     * @return string
     */
    public function index(Layout $layout)
    {
        return $layout->load(['default'])->render();
    }

    /**
     * Render layout home page.
     *
     * @return string
     */
    public function home(Layout $layout)
    {
        return $layout->load(['default', home])->render();
    }

}
