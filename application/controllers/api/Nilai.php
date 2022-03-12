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
        $this->load->model('Mahasiswa_model');
        $this->load->model('Dosen_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mata_kuliah_model');
    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $Nilai = $this->Nilai_model->get_nilai();

            foreach ($Nilai as $key => $value) {
                $Nilai[$key]['mahasiswa'] = $this->Mahasiswa_model->get_mahasiswa($value['nama_mahasiswa']);
                $Nilai[$key]['mahasiswa'][0]['kelas'] = $this->Kelas_model->get_kelas($value['kelas']);
                $Nilai[$key]['mata_kuliah'] = $this->Mata_kuliah_model->get_mata_kuliah($value['nama_matakuliah']);
                $Nilai[$key]['mata_kuliah'][0]['dosen'] = $this->Dosen_model->get_dosen($value['nama_dosen']);
            }

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
            $this->form_validation->set_data($this->put());
            $this->form_validation->set_rules('nama_mahasiswa', 'Nama Mahasiswa', 'trim|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'trim|required');
            $this->form_validation->set_rules('nama_matakuliah', 'Nama Mata Kuliah', 'trim|required');
            $this->form_validation->set_rules('nilai', 'Nilai', 'trim|required|numeric');
            $this->form_validation->set_rules('nama_dosen', 'Nama Dosen', 'trim|required');
            if (!$this->form_validation->run()) {
                throw new Exception(validation_errors());
            }

            $id = $this->put('id');
            $data = [
                'nama_mahasiswa' => $this->put('nama_mahasiswa'),
                'kelas' => $this->put('kelas'),
                'nama_matakuliah' => $this->put('nama_matakuliah'),
                'nilai' => $this->put('nilai'),
                'nama_dosen' => $this->put('nama_dosen'),
            ];
            $nilai = $this->Nilai_model->get_nilai($id);

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an ID',
                ], REST_CONTROLLER::HTTP_BAD_REQUEST);
            }

            // if data doesnt change then return ok
            if ($nilai[0]['nama_mahasiswa'] === $data['nama_mahasiswa'] && $nilai[0]['kelas'] === $data['kelas'] && $nilai[0]['nama_matakuliah'] === $data['nama_matakuliah'] && $nilai[0]['nilai'] === $data['nilai'] && $nilai[0]['nama_dosen'] === $data['nama_dosen']) {
                $this->response([
                    'status' => true,
                    'message' => 'Nilai succesfully updated',
                ], REST_CONTROLLER::HTTP_OK);
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

    public function view_nilai_get()
    {
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
