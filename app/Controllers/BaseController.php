<?php

namespace App\Controllers;

use CodeIgniter\Controller;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
    }
}