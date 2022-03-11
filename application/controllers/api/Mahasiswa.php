<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 300;
        $this->methods['index_post']['limit'] = 300;
        $this->methods['index_delete']['limit'] = 300;
        $this->methods['index_put']['limit'] = 300;

        $this->load->model('Mahasiswa_model');
        $this->load->model('Kelas_model');

    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $mahasiswa = $this->Mahasiswa_model->get_mahasiswa();
        } else {
            $mahasiswa = $this->Mahasiswa_model->get_mahasiswa($id);
        }

        if ($mahasiswa) {
            $this->response([
                'status' => true,
                'data' => $mahasiswa,
            ], REST_CONTROLLER::HTTP_OK);
        }

        if ($mahasiswa === null) {
            $this->response([
                'status' => false,
                'message' => 'Mahasiswa not found',
            ], REST_CONTROLLER::HTTP_NOT_FOUND);
        }

        $this->response([
            'status' => false,
            'message' => 'User not found',
        ], REST_CONTROLLER::HTTP_NOT_FOUND);
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an ID',
            ], REST_CONTROLLER::HTTP_BAD_REQUEST);
        } else {
            if ($this->Mahasiswa_model->delete_mahasiswa($id) > 0) {
                $this->response([
                    'status' => false,
                    'id' => $id,
                    'message' => 'Deleted',
                ], REST_CONTROLLER::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'ID not found',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {
        try {
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|is_unique[mahasiswa.no_telp]|numeric|max_length[12]');
            $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'trim|required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
            $this->form_validation->set_rules('nim', 'NIM', 'trim|required|numeric|is_unique[mahasiswa.nim]|max_length[10]');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $data = [
                'nim' => $this->post('nim'),
                'nama' => $this->post('nama'),
                'alamat' => $this->post('alamat'),
                'no_telp' => $this->post('no_telp'),
                'id_kelas' => $this->post('id_kelas'),
            ];

            if ($this->Mahasiswa_model->create_mahasiswa($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Mahasiswa succesfully created',
                ], REST_CONTROLLER::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'failed to create data',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $e) {
            $this->response([
                'status' => false,
                'message' => $e->getMessage(),
            ], REST_CONTROLLER::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        try {
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|is_unique[mahasiswa.no_telp]|numeric|max_length[12]');
            $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'trim|required|numeric');
            $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
            $this->form_validation->set_rules('nim', 'NIM', 'trim|required|numeric|is_unique[mahasiswa.nim]|max_length[10]');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $data = [
                'nim' => $this->post('nim'),
                'nama' => $this->post('nama'),
                'alamat' => $this->post('alamat'),
                'no_telp' => $this->post('no_telp'),
                'id_kelas' => $this->post('id_kelas'),
            ];

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            if ($this->Mahasiswa_model->update_mahasiswa($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Mahasiswa succesfully updated',
                ], REST_CONTROLLER::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Failed to update data',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $e) {
            $this->response([
                'status' => false,
                'message' => $e->getMessage(),
            ], REST_CONTROLLER::HTTP_BAD_REQUEST);
        }
    }
}
