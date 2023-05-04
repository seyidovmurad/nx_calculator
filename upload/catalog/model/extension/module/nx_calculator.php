<?php
  class ModelExtensionModuleNxCalculator extends Model {

    public function getFormula($product_id) {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula_table` AS nft, `".DB_PREFIX."nxc_formula` AS nf WHERE nft.nxc_formula_id = nf.nxc_formula_id AND nft.table_id = $product_id;");
        return $query->rows[0];
    }

    public function getRoute($product_id) {
      $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula_table` AS nft, `".DB_PREFIX."nxc_route` AS nr WHERE nft.nxc_route_id = nr.nxc_route_id AND nft.table_id = 40;");
      return $query->rows[0];
    }
  }