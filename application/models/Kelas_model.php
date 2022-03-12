<?php

class Kelas_model extends CI_Model
{

    public function get_kelas($id = null)
    {
        if ($id === null) {
            return $this->db->order_by('nama_kelas', 'ASC')->get('kelas')->result_array();
        }
        return $this->db->order_by('nama_kelas', 'ASC')->get_where('kelas', ['id' => $id])->result_array();
    }

    public function delete_kelas($id)
    {
        $this->db->delete('kelas', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function create_kelas($data)
    {
        $this->db->insert('kelas', $data);
        return $this->db->affected_rows();
    }

    public function update_kelas($data, $id)
    {
        $this->db->update('kelas', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

}
