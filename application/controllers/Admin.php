<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
  function __construct(){
      parent::__construct();
        $this->load->model('batik_model');
      $this->load->helper('url');
      if($this->session->userdata('status') != "login"){
        redirect(base_url("login"));
          $this->session->set_userdata('username','1');
      }

  }


  public function index($halaman='index'){

    $data['depan']=$this->batik_model->get_frontend();

    $data['batik']=$this->batik_model->get_batik();

    $this->load->view('templates/header');
    $this->load->view('admin/'.$halaman,$data);
    $this->load->view('templates/footer');
  }

  public function create(){
      $this->load->helper('form');
    $this->load->library('form_validation');


     $this->form_validation->set_rules('nama','nama','required');
     $this->form_validation->set_rules('harga','harga','required');

     $this->form_validation->set_rules('deskripsi','deskripsi','required');


       $nama = 'file_'.time();

      $config['upload_path']	 = './assets/img/uploads/';

      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']		 = '5000';
      $config['max_width']	 = '5000';
      $config['max_height']	 = '5000';
      $config['file_name']	 = $nama;

    $this->load->library('upload', $config);


     if ($this->form_validation->run() == TRUE && $this->upload->do_upload('foto'))
      {

        $gambar = $this->upload->data();

        $data = array(
        'nama_product'=> $this->input->post('nama',TRUE),
        'harga'=> $this->input->post('harga',TRUE),
        'gambar'		=> $gambar['file_name'],
        'deskripsi'=> $this->input->post('deskripsi',TRUE)

      );

      $this->batik_model->insert($data);
       redirect ('admin/post');
      }  else   {
        $data['foto'] = '';
        if (! $this->upload->do_upload('foto'))
        {
           $error = array('error' => $this->upload->display_errors());

                        $this->load->view('admin/post', $error);
        }


  }
 }





 public function update($id){
   $this->load->helper('form');
   $this->load->library('form_validation');

   $this->form_validation->set_rules('nama','nama','required');
   $this->form_validation->set_rules('harga','harga','required');
   $this->form_validation->set_rules('deskripsi','deskripsi','required');

   $nama = 'file_'.time();
   $config['upload_path']	 = './assets/img/uploads/';
   $config['allowed_types'] = 'gif|jpg|png';
   $config['max_size']		 = '5000';
   $config['max_width']	 = '5000';
   $config['max_height']	 = '5000';
   $config['file_name']	 = $nama;

   $this->load->library('upload', $config);

   if ($this->form_validation->run()=== FALSE) {
       $data['batik'] = $this->batik_model->get_batik_id($id);
       $this->load->view('templates/header');
       $this->load->view('admin/update',$data);
       $this->load->view('templates/footer');
   } else {
       if ($this->upload->do_upload('foto')) {
           $gambar = $this->upload->data();
           $data = array(
               'nama_product'=> $this->input->post('nama',TRUE),
               'harga'=> $this->input->post('harga',TRUE),
               'gambar'		=> $gambar['file_name'],
               'deskripsi'=> $this->input->post('deskripsi',TRUE)
           );
       } else {
           $data = array(
               'nama_product'=> $this->input->post('nama',TRUE),
               'harga'=> $this->input->post('harga',TRUE),
               'deskripsi'=> $this->input->post('deskripsi',TRUE)
           );
      }
       if ($this->batik_model->update_batik($id, $data) === TRUE ) {
            //$data['id'] = ;

           redirect('admin/update/'.$id);
       }else {
           echo "salah";
       }
   }
}


public function delete($id){
  $this->batik_model->delete_data($id);
  redirect('admin/table');
}





}
