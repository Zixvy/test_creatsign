<?php

class Mahasiswa_model extends CI_Model
{

    public function get_mahasiswa($id = null)
    {
        if ($id === null) {
            return $this->db->order_by('id', 'DESC')->get('mahasiswa')->result_array();
        }
        return $this->db->order_by('id', 'DESC')->get_where('mahasiswa', ['id' => $id])->result_array();
    }

    public function delete_mahasiswa($id)
    {
        $this->db->delete('mahasiswa', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function create_mahasiswa($data)
    {
        $this->db->insert('mahasiswa', $data);
        return $this->db->affected_rows();
    }

    public function update_mahasiswa($data, $id)
    {
        $this->db->update('mahasiswa', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function get_kelas_mahasiswa()
    {
        $this->db->select('id', 'nama_kelas')->order_by('nama_kelas', 'ASC')->get('kelas')->result_array();
        return $this->db->affected_rows();
    }

}
