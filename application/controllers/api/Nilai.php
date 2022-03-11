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
            $Kelas = $this->Kelas_model->get_kelas();
            $Matkul = $this->Mata_kuliah_model->get_mata_kuliah();
            $Dosen = $this->Dosen_model->get_dosen();
            $Nilai = $this->Nilai_model->get_nilai();

            // get nilai by id_mahasiswa
            foreach($Nilai as $key => $value) {
                $Nilai[$key]['mahasiswa'] = $this->Mahasiswa_model->get_mahasiswa($value['nama_mahasiswa']);
                $Nilai[$key]['mahasiswa']['kelas'] = $this->Kelas_model->get_kelas($value['kelas']);
                $Nilai[$key]['mata_kuliah'] = $this->Mata_kuliah_model->get_mata_kuliah($value['nama_matakuliah']);
                $Nilai[$key]['mata_kuliah']['dosen'] = $this->Dosen_model->get_dosen($value['nama_dosen']);
                // foreach($Kelas as $key2 => $value2) {
                //     if($value['kelas'] == $value2['id']) {
                //         $Nilai[$key]['kelas'] = $this->Kelas_model->get_kelas($value['id']);
                //     }
                // }
                //     foreach($Matkul as $key3 => $value3) {
                //         if($value['nama_matakuliah'] == $value3['id']) {
                //             $Matkul[$key]['nama_matakuliah'] = $this->Mata_kuliah_model->get_mata_kuliah($value['id']);
                //         }
                //         foreach($Dosen as $key4 => $value4) {
                //             if($value['nama_dosen'] == $value4['id']) {
                //                 $Dosen[$key]['dosen'] = $this->Dosen_model->get_dosen($value['id']);
                //             }
                //         }
                //     }
                // }

                // merge all foreach into 1 array
                // $Nilai[$key]['kelas'] = $Kelas;
                // $Nilai[$key]['matkul'] = $Matkul;
                // $Nilai[$key]['dosen'] = $Dosen;
                
                

                // $Nilai[$key]['mata_kuliah'] = $this->Mata_kuliah_model->get_mata_kuliah($value['id']);
                // $Nilai[$key]['dosen'] = $this->Dosen_model->get_dosen($value['id']);
            }
            // filter the data from nilai, mahasiswa, mata_kuliah, dosen, kelas
            // $Nilai = array_map(function($Nilai) {
            //     return [
            //         'id' => $Nilai['id'],
            //         'id_mahasiswa' => $Nilai['id_mahasiswa'],
            //         'id_dosen' => $Nilai['id_dosen'],
            //         'id_kelas' => $Nilai['id_kelas'],
            //         'kode_matakuliah' => $Nilai['kode_matakuliah'],
            //         'nilai' => $Nilai['nilai'],
            //         'mahasiswa' => $Nilai['mahasiswa'],
            //         'dosen' => $Nilai['dosen'],
            //         'kelas' => $Nilai['kelas'],
            //         'mata_kuliah' => $Nilai['mata_kuliah'],
            //     ];
            // }, $Nilai);

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
