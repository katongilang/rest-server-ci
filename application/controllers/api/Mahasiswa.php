<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Mahasiswa extends Rest_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mahasiswa_model','mahasiswa'); //mahasiswa itu nama alias

		# MEMBERI LIMIT PER METHOD
		#$this->method['method']['limit'] = NUM_REQUESTS_PER_HOUR;
		$this->methods['index_get']['limit'] = 500;
		$this->methods['index_delete']['limit'] = 100;		
		$this->methods['index_post']['limit'] = 100;
	}

	public function index_get(){
		$id = $this->get('id');
		if ($id === null) {
			$mahasiswa = $this->mahasiswa->getMahasiswa();
		}else{
			$mahasiswa = $this->mahasiswa->getMahasiswa($id);
		}
		

		if ($mahasiswa) {
			// Set the response and exit
			$this->response([
				'status' => TRUE,
				'data' => $mahasiswa,
                ], REST_Controller::HTTP_OK); // ok (200) being the HTTP response code
		}
		else{
			$this->response([
				'status' => FALSE,
				'data' => 'ID Not Found !',
                ], REST_Controller::HTTP_NOT_FOUND); // not found (404) being the HTTP response code
		}

	}

	public function index_delete(){
		$id = $this->delete('id');

		if ($id === null) {
			$this->response([
				'status' => FALSE,
				'data' => 'Provide an ID !',
                ], REST_Controller::HTTP_BAD_REQUEST); // bad req (400) being the HTTP response code
		}else{
			if( $this->mahasiswa->deleteMahasiswa($id) > 0 ){
				// OK
				$this->response([
					'status' => TRUE,
					'data' => 'deleted !',
                ], REST_Controller::HTTP_OK); // Harusnya HTTP_NO_CONTENT , tapi ga tau knapa bermasalah
			}else{
				// ID Not Found
				$this->response([
					'status' => FALSE,
					'data' => 'ID Not Found !',
                ], REST_Controller::HTTP_NOT_FOUND); // not found (404) being the HTTP response code
			}
		}
	}

	public function index_post(){
		$data = [
			'nrp' => $this->post('nrp'),
			'nama' => $this->post('nama'),
			'email' => $this->post('email'),
			'jurusan' => $this->post('jurusan'),
		];

		if( $this->mahasiswa->createMahasiswa($data) > 0 ){
			// OK
			$this->response([
				'status' => TRUE,
				'data' => 'new mahasiswa has been created !',
			], REST_Controller::HTTP_CREATED);
		}
		else{
			// ID Not Found
			$this->response([
				'status' => FALSE,
				'data' => 'Failed to create new data! !',
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function index_put(){
		$id = $this->put('id');

		$data = [
			'nrp' => $this->put('nrp'),
			'nama' => $this->put('nama'),
			'email' => $this->put('email'),
			'jurusan' => $this->put('jurusan'),
		];

		if( $this->mahasiswa->updateMahasiswa($data,$id) > 0 ){
			// OK
			$this->response([
				'status' => TRUE,
				'messege' => 'Mahasiswa has been updated !',
			], REST_Controller::HTTP_OK);
		}
		else{
			// ID Not Found
			$this->response([
				'status' => FALSE,
				'messege' => 'Failed to update data !',
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}