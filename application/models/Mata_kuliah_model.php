<?php

class Mata_kuliah_model extends CI_Model
{

    public function get_mata_kuliah($id = null)
    {
        if ($id === null) {
            return $this->db->order_by('nama_matakuliah', 'ASC')->get('mata_kuliah')->result_array();
        }
        return $this->db->order_by('nama_matakuliah', 'ASC')->get_where('mata_kuliah', ['kode_matakuliah' => $id])->result_array();
    }

    public function delete_mata_kuliah($id)
    {
        $this->db->delete('mata_kuliah', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function create_mata_kuliah($data)
    {
        $this->db->insert('mata_kuliah', $data);
        return $this->db->affected_rows();
    }

    public function update_mata_kuliah($data, $id)
    {
        $this->db->update('mata_kuliah', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

}
