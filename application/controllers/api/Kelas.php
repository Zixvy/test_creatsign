<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Kelas extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 300;
        $this->methods['index_post']['limit'] = 300;
        $this->methods['index_delete']['limit'] = 300;
        $this->methods['index_put']['limit'] = 300;

        $this->load->model('Kelas_model');

    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $kelas = $this->Kelas_model->get_kelas();
        } else {
            $kelas = $this->Kelas_model->get_kelas($id);
        }

        if ($kelas) {
            $this->response([
                'status' => true,
                'data' => $kelas,
            ], REST_CONTROLLER::HTTP_OK);
        }

        $this->response([
            'status' => false,
            'message' => 'Kelas not found',
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
            if ($this->Kelas_model->delete_kelas($id) > 0) {
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
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'trim|required');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $data = [
                'nama_kelas' => $this->post('nama_kelas'),
            ];

            if ($this->Kelas_model->create_kelas($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'kelas succesfully created',
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
            $this->form_validation->set_data($this->put());
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'trim|required');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $data = [
                'nama_kelas' => $this->put('nama_kelas'),
            ];
            $kelas = $this->Kelas_model->get_kelas($id);

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            // if data doesnt change then return ok
            if ($kelas[0]['nama_kelas'] === $data['nama_kelas']) {
                $this->response([
                    'status' => true,
                    'message' => 'kelas succesfully updated',
                ], REST_CONTROLLER::HTTP_OK);
            }

            if ($this->Kelas_model->update_kelas($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'kelas succesfully updated',
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
