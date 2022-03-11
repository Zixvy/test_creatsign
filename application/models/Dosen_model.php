<?php

class Dosen_model extends CI_Model
{

    public function get_dosen($id = null)
    {
        if ($id === null) {
            return $this->db->get('dosen')->result_array();
        }
        return $this->db->get_where('dosen', ['id' => $id])->result_array();
    }

    public function delete_dosen($id)
    {
        $this->db->delete('dosen', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function create_dosen($data)
    {
        $this->db->insert('dosen', $data);
        return $this->db->affected_rows();
    }

    public function update_dosen($data, $id)
    {
        $this->db->update('dosen', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

}
