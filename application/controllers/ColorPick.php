<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ColorPick extends CI_Controller {

    public function index()
    {
        $this->load->view('pages/header');
        $this->load->view('pages/color');
        $this->load->view('pages/footer');
    }
}
