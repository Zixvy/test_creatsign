<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Dosen extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 300;
        $this->methods['index_post']['limit'] = 300;
        $this->methods['index_delete']['limit'] = 300;
        $this->methods['index_put']['limit'] = 300;

        $this->load->model('Dosen_model');

    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $dosen = $this->Dosen_model->get_dosen();
        } else {
            $dosen = $this->Dosen_model->get_dosen($id);
        }

        if ($dosen) {
            $this->response([
                'status' => true,
                'data' => $dosen,
            ], REST_CONTROLLER::HTTP_OK);
        }

        if ($dosen === null) {
            $this->response([
                'status' => false,
                'message' => 'Dosen not found',
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
            if ($this->Dosen_model->delete_dosen($id) > 0) {
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
            $this->form_validation->set_rules('nama_dosen', 'Nama Dosen', 'trim|required');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|is_unique[dosen.no_telp]|numeric|max_length[12]');
            $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
            $this->form_validation->set_rules('nip', 'NIP', 'trim|required|numeric|is_unique[dosen.nip]|max_length[10]');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $data = [
                'nip' => $this->post('nip'),
                'nama_dosen' => $this->post('nama_dosen'),
                'alamat' => $this->post('alamat'),
                'no_telp' => $this->post('no_telp'),
            ];

            if ($this->Dosen_model->create_dosen($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Dosen succesfully created',
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
            $this->form_validation->set_rules('nama_dosen', 'Nama Dosen', 'trim|required');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|is_unique[dosen.no_telp]|numeric|max_length[12]');
            $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
            $this->form_validation->set_rules('nip', 'NIP', 'trim|required|numeric|is_unique[dosen.nip]|max_length[10]');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $data = [
                'nip' => $this->post('nip'),
                'nama_dosen' => $this->post('nama_dosen'),
                'alamat' => $this->post('alamat'),
                'no_telp' => $this->post('no_telp'),
            ];

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            if ($this->Dosen_model->update_dosen($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'dosen succesfully updated',
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
