<?php

class Nilai_model extends CI_Model
{

    public function get_nilai($id = null)
    {
        if ($id === null) {
            return $this->db->get('nilai')->result_array();
        }
        return $this->db->get_where('nilai', ['id' => $id])->result_array();
    }

    public function get_view_nilai() 
    {
        $this->db->select('mahasiswa.nama, kelas.nama_kelas, mata_kuliah.nama_matakuliah, nilai.nilai, dosen.nama_dosen');
        $this->db->from('nilai');
        $this->db->join('mahasiswa', 'mahasiswa.id = nilai.nama_mahasiswa');
        $this->db->join('kelas', 'kelas.id = nilai.kelas');
        $this->db->join('mata_kuliah', 'mata_kuliah.kode_matakuliah = nilai.nama_matakuliah');
        $this->db->join('dosen', 'dosen.id = nilai.nama_dosen');
        return $this->db->get()->result_array();
    }

    public function delete_nilai($id)
    {
        $this->db->delete('nilai', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function create_nilai($data)
    {
        $this->db->insert('nilai', $data);
        return $this->db->affected_rows();
    }

    public function update_nilai($data, $id)
    {
        $this->db->update('nilai', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

   

}
