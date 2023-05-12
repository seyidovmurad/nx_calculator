<?php
  class ModelExtensionModuleNxCalculator extends Model {

    public function getFormula($product_id) {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula_table` AS nft, `".DB_PREFIX."nxc_formula` AS nf WHERE nft.nxc_formula_id = nf.nxc_formula_id AND nft.table_id = $product_id;");
        return $query->rows[0];
    }


    public function getFormulaFromTable($product_id, $table = 0) {
      //product-0 category-1 manifacture-2
      if($table = 1) {
        $query = $this->db->query("SELECT nf.formula FROM `".DB_PREFIX."product_to_category` AS ptc, `".DB_PREFIX."nxc_formula_table` AS nft, `".DB_PREFIX."nxc_formula` AS nf WHERE ptc.category_id = nft.table_id AND nft.table = $table AND nf.nxc_formula_id = nft.nxc_formula_id AND ptc.product_id = $product_id;");
      }
      else if($table = 2) {
        $query = $this->db->query("SELECT nf.formula FROM `".DB_PREFIX."product` AS p, `".DB_PREFIX."nxc_formula_table` AS nft, `".DB_PREFIX."nxc_formula` AS nf WHERE p.manufacturer_id = nft.table_id AND nft.table = $table AND nf.nxc_formula_id = nft.nxc_formula_id AND p.product_id = $product_id;");
      }
      return $query->rows;
    }

    public function getFormulaAdv($product_id) {
      // SELECT DISTINCT nf.formula, nf.priority FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_to_category` AS ptc ON p.product_id = ptc.product_id JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON (p.product_id = nft.table_id AND nft.table = 0) OR (ptc.category_id = nft.table_id AND nft.table = 1) OR (p.manufacturer_id = nft.table_id  AND nft.table = 2) JOIN `".DB_PREFIX."nxc_formula` AS nf ON nf.nxc_formula_id = nft.nxc_formula_id WHERE (p.product_id = 40 AND (nft.table = 0 OR nft.table = 2)) OR (ptc.product_id = 40 AND nft.table = 1);

      $query = $this->db->query("SELECT DISTINCT nf.formula, nft.priority, nf.value, nr.html_id FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_to_category` AS ptc ON p.product_id = ptc.product_id JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON (p.product_id = nft.table_id AND nft.table = 0) OR (ptc.category_id = nft.table_id AND nft.table = 1) OR (p.manufacturer_id = nft.table_id  AND nft.table = 2) OR nft.table = -1 JOIN `".DB_PREFIX."nxc_formula` AS nf ON nf.nxc_formula_id = nft.nxc_formula_id JOIN `".DB_PREFIX."nxc_route` AS nr ON nr.nxc_route_id = nft.nxc_route_id WHERE ((p.product_id = $product_id AND (nft.table = 0 OR nft.table = 2)) OR (ptc.product_id = $product_id AND nft.table = 1) OR nft.table = -1) AND nf.status = 1 ORDER BY nft.priority DESC;");
      return $query->rows;
    }
  }