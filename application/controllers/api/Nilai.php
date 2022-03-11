<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Nilai extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 300;
        $this->methods['index_post']['limit'] = 300;
        $this->methods['index_delete']['limit'] = 300;
        $this->methods['index_put']['limit'] = 300;

        $this->load->model('Nilai_model');

    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $Nilai = $this->Nilai_model->get_nilai();
        } else {
            $Nilai = $this->Nilai_model->get_nilai($id);
        }

        if ($Nilai) {
            $this->response([
                'status' => true,
                'data' => $Nilai,
            ], REST_CONTROLLER::HTTP_OK);
        }

        if ($Nilai === null) {
            $this->response([
                'status' => false,
                'message' => 'Nilai not found',
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
            if ($this->Nilai_model->delete_nilai($id) > 0) {
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
            $this->form_validation->set_rules('nama_mahasiswa', 'Nama Mahasiswa', 'trim|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'trim|required');
            $this->form_validation->set_rules('nama_matakuliah', 'Nama Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('nilai', 'Nilai', 'trim|required|numeric');
            $this->form_validation->set_rules('nama_dosen', 'Nama Dosen', 'trim|required');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $data = [
                'nama_mahasiswa' => $this->post('nama_mahasiswa'),
                'kelas' => $this->post('kelas'),
                'nama_matakuliah' => $this->post('nama_matakuliah'),
                'nilai' => $this->post('nilai'),
                'nama_dosen' => $this->post('nama_dosen'),
            ];

            if ($this->Nilai_model->create_nilai($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Nilai succesfully created',
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
            $this->form_validation->set_rules('nama_matakuliah', 'Nama Nilai', 'trim|required');
            $this->form_validation->set_rules('kode_matakuliah', 'Kode Nilai', 'trim|required|numeric');
            $this->form_validation->set_rules('id_dosen', 'ID Dosen', 'trim|required|numeric');

            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $data = [
                'nama_mahasiswa' => $this->post('nama_mahasiswa'),
                'kelas' => $this->post('kelas'),
                'nama_matakuliah' => $this->post('nama_matakuliah'),
                'nilai' => $this->post('nilai'),
                'nama_dosen' => $this->post('nama_dosen'),
            ];

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            if ($this->Nilai_model->update_nilai($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Nilai succesfully updated',
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

    public function view_nilai_get() {
        $id = $this->get('id');

        if ($id === null) {
            $Nilai = $this->Nilai_model->get_view_nilai();
        } else {
            $Nilai = $this->Nilai_model->get_view_nilai($id);
        }

        if ($Nilai) {
            $this->response([
                'status' => true,
                'data' => $Nilai,
            ], REST_CONTROLLER::HTTP_OK);
        }

        if ($Nilai === null) {
            $this->response([
                'status' => false,
                'message' => 'Nilai not found',
            ], REST_CONTROLLER::HTTP_NOT_FOUND);
        }

        $this->response([
            'status' => false,
            'message' => 'User not found',
        ], REST_CONTROLLER::HTTP_NOT_FOUND);
    }
}
