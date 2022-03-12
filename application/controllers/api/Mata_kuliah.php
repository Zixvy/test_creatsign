<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mata_kuliah extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 300;
        $this->methods['index_post']['limit'] = 300;
        $this->methods['index_delete']['limit'] = 300;
        $this->methods['index_put']['limit'] = 300;

        $this->load->model('Mata_kuliah_model');

    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $mata_kuliah = $this->Mata_kuliah_model->get_mata_kuliah();
        } else {
            $mata_kuliah = $this->Mata_kuliah_model->get_mata_kuliah($id);
        }

        if ($mata_kuliah) {
            $this->response([
                'status' => true,
                'data' => $mata_kuliah,
            ], REST_CONTROLLER::HTTP_OK);
        }

        $this->response([
            'status' => false,
            'message' => 'Mata Kuliah not found',
        ], REST_CONTROLLER::HTTP_NOT_FOUND);
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an ID',
                'id' => $id,
            ], REST_CONTROLLER::HTTP_BAD_REQUEST);
        } else {
            if ($this->Mata_kuliah_model->delete_mata_kuliah($id) > 0) {
                $this->response([
                    'status' => false,
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
            $this->form_validation->set_rules('nama_matakuliah', 'Nama Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('kode_matakuliah', 'Kode Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('id_dosen', 'ID Dosen', 'trim|required|numeric');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $data = [
                'nama_matakuliah' => $this->post('nama_matakuliah'),
                'kode_matakuliah' => $this->post('kode_matakuliah'),
                'id_dosen' => $this->post('id_dosen'),
            ];

            if ($this->Mata_kuliah_model->create_mata_kuliah($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Mata Kuliah succesfully created',
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
            $this->form_validation->set_rules('nama_matakuliah', 'Nama Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('kode_matakuliah', 'Kode Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('id_dosen', 'ID Dosen', 'trim|required|numeric');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $kode_matakuliah = $this->put('kode_matakuliah');
            $mata_kuliah = $this->Mata_kuliah_model->get_mata_kuliah($kode_matakuliah);
            $data = [
                'nama_matakuliah' => $this->put('nama_matakuliah'),
                'kode_matakuliah' => $this->put('kode_matakuliah'),
                'id_dosen' => $this->put('id_dosen'),
            ];

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            // if data doesnt change then do nothing
            if ($mata_kuliah[0]['nama_matakuliah'] == $data['nama_matakuliah'] && $mata_kuliah[0]['kode_matakuliah'] == $data['kode_matakuliah'] && $mata_kuliah[0]['id_dosen'] == $data['id_dosen']) {
                $this->response([
                    'status' => true,
                    'message' => 'Mata Kuliah succesfully updated',
                ], REST_CONTROLLER::HTTP_OK);
            }

            if ($this->Mata_kuliah_model->update_mata_kuliah($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Mata Kuliah succesfully updated',
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
