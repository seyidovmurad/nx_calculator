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
    }