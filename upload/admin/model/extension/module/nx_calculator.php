<?php
    class ModelExtensionModuleNxCalculator extends Model {

        public function addRoute($data) {
            $this->db->query("INSERT INTO `". DB_PREFIX . "nxc_route` SET html_id = '". 
                                $this->db->escape($data['html_id']) . "', 
                                name = '". $this->db->escape($data['name']) . "', 
                                url = '". $this->db->escape($data['url']) . "';");
        }


        public function getRoutes() {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_route`");
            return $query->rows;
        }

        public function getFormulas() {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula`");
            return $query->rows;
        }

        public function routeExist($route) {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_added_route` WHERE `url`= '$route';");
            return $query->rows != null;
        }

        public function addRouteToExistingRoute($url) {
            $this->db->query("INSERT IGNORE INTO `".DB_PREFIX."nxc_added_route` SET url = '".$this->db->escape($url)."' ;");
        }

        public function addFormula($data) {
            $this->db->query("INSERT INTO `".DB_PREFIX."nxc_formula` SET name = '".$this->db->escape($data['name'])."', value = " .(int)$data['value'].", formula = '" .$this->db->escape($data['formula']). "', status = " .(int)$data['status']." ;");
        }

        public function addFormulaToTable($data) {
            $this->db->query("INSERT INTO `".DB_PREFIX."nxc_formula_table` SET `nxc_formula_id` = '".(int)$data['nxc_formula_id']."', `nxc_route_id` = " .(int)$data['nxc_route_id']. ", `table_id` = " .(int)$data['table_id']. ", `table` = " .(int)$data['table'].", `priority` = 1 ;");
        }

        public function getTotalFormulas() {
            $count = $this->db->query("SELECT COUNT(`nxc_formula_id`) FROM `".DB_PREFIX."nxc_formula` WHERE 1;");
        }

        public function autoComplete($searchTerm) {
            $query = $this->db->query("SELECT pd.name, p.product_id AS id FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id WHERE pd.name LIKE '%".$searchTerm."%' AND status = 1 ORDER BY name ASC;");
            return $query->rows;
        }

        public function getTable($type) {
            $query= array();
            if($type == 0){
                $query = $this->db->query("SELECT DISTINCT pd.name, p.product_id AS id FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id WHERE status = 1 ORDER BY name ASC;");
            }
            else if($type == 1) {
                $query = $this->db->query("SELECT DISTINCT cd.name, c.category_id AS id FROM `".DB_PREFIX."category` AS c JOIN `".DB_PREFIX."category_description` AS cd ON c.category_id = cd.category_id ORDER BY cd.name ASC;");
            }
            else if($type == 2) {
                $query = $this->db->query("SELECT DISTINCT m.name, m.manufacturer_id AS id  FROM `".DB_PREFIX."manufacturer` AS m JOIN `".DB_PREFIX."product` AS p ON m.manufacturer_id = p.manufacturer_id ORDER BY m.name ASC;");
            }
            return isset($query->rows) ? $query->rows : array();
        }
    }