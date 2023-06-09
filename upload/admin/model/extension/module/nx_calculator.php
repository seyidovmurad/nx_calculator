<?php
    class ModelExtensionModuleNxCalculator extends Model {

        public function addRoute($data) {
            $this->db->query("INSERT INTO `". DB_PREFIX . "nxc_route` SET html_id = '". 
                                $this->db->escape($data['html_id']) . "', 
                                name = '". $this->db->escape($data['name']) . "';");
        }

        public function addRouteToExistingRoute($url) {
            $this->db->query("INSERT IGNORE INTO `".DB_PREFIX."nxc_added_route` SET url = '".$this->db->escape($url)."' ;");
        }

        public function addFormula($data) {
            $this->db->query("INSERT INTO `".DB_PREFIX."nxc_formula` SET name = '".$this->db->escape($data['name'])."', value = " .(int)$data['value'].", formula = '" .$this->db->escape($data['formula']). "', status = " .(int)$data['status']." ;");

            // $formula_id = $this->db->getLastId();
            // $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nxc_language` (
            //     `nxc_language_id` int(11) NOT NULL AUTO_INCREMENT,
            //     `nxc_formula_id` int(11) NOT NULL,
            //     `language_id` int(11) NOT NULL,
            //     `text` varchar(50) NOT NULL,
            //     PRIMARY KEY (`nxc_language_id`))"
            // );
            
            // foreach($data['text'] as $lang=> $text) {

            //     $this->db->query("INSERT INTO `".DB_PREFIX."nxc_language`(`nxc_formula_id`, `language_id`, `text`) VALUES ('$formula_id','$lang','$text')");
            // }
        
        }

        public function addFormulaToTable($data) {
            $this->db->query("INSERT INTO `".DB_PREFIX."nxc_formula_table` SET `nxc_formula_id` = '".(int)$data['nxc_formula_id']."', `nxc_route_id` = " .(int)$data['nxc_route_id']. ", `table_id` = " .(int)$data['table_id']. ", `table` = " .(int)$data['table'].", `priority` = ".(int)$data['priority']. ", `status` = ".(int)$data['status']." ;");
        }


        public function getRoutes() {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_route`");
            return $query->rows;
        }

        public function getFormulas() {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula`");
            return $query->rows;
        }

        public function getFormula($id) {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula` WHERE nxc_formula_id = $id;");
            return $query->rows[0];
        }

        public function getFormText($id) {
            $query = $this->db->query("SELECT language_id, text FROM `".DB_PREFIX."nxc_language` WHERE nxc_formula_id = $id;");

            foreach($query->rows as $obj) {
                $array[$obj['language_id']] = $obj['text'];
            }
            return isset($array) ? $array : array();
        }

        public function getTable($type, $id) {
            $query= array();
            if($id == -1){
                if($type == 0){
                    $query = $this->db->query("SELECT DISTINCT p.model AS `name`, p.product_id AS id FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id WHERE status = 1 AND pd.language_id = ".(int)$this->config->get('config_language_id')." ORDER BY name ASC;");
                }
                else if($type == 1) {
                    $query = $this->db->query("SELECT DISTINCT cd.name, c.category_id AS id FROM `".DB_PREFIX."category` AS c JOIN `".DB_PREFIX."category_description` AS cd ON c.category_id = cd.category_id WHERE cd.language_id = ".(int)$this->config->get('config_language_id')."  ORDER BY cd.name ASC;");
                }
                else if($type == 2) {
                    $query = $this->db->query("SELECT DISTINCT m.name, m.manufacturer_id AS id  FROM `".DB_PREFIX."manufacturer` AS m JOIN `".DB_PREFIX."product` AS p ON m.manufacturer_id = p.manufacturer_id ORDER BY m.name ASC;");
                }
            }
            else {
                if($type == 0) {
                    $query = $this->db->query("SELECT pd.name, pd.product_id AS id FROM `".DB_PREFIX."product_description` AS pd JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON nft.table_id = pd.product_id AND nft.table = 0 WHERE nft.nxc_formula_id = $id AND pd.language_id = ".(int)$this->config->get('config_language_id')." ORDER BY pd.name ASC;");
                }
                else if($type == 1) {
                    $query = $this->db->query("SELECT cd.name, cd.category_id AS id FROM `".DB_PREFIX."category_description` AS cd JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON nft.table_id = cd.category_id AND nft.table = 1 WHERE nft.nxc_formula_id = $id AND cd.language_id = ".(int)$this->config->get('config_language_id')." ORDER BY cd.name ASC ;"); //LIMIT 1 OFFSET 1 add end of it 
                }
                else if($type == 2) {
                    $query = $this->db->query("SELECT m.name, m.manufacturer_id AS id FROM `".DB_PREFIX."manufacturer` AS m JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON nft.table_id = manufacturer_id AND nft.table = 2 WHERE nft.nxc_formula_id = $id ORDER BY m.name ASC;");
                }
            }
            return isset($query->rows) ? $query->rows : array();
        }

        public function getProducts($id) {
            $products = array();
            $query = $this->db->query("SELECT DISTINCT p.product_id, pd.name FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON nft.table_id = p.product_id AND nft.table = 0 WHERE nft.nxc_formula_id = $id;");
            $products = array_merge($products, $query->rows);
            $query = $this->db->query("SELECT p.product_id, pd.name FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id JOIN `".DB_PREFIX."product_to_category` AS ptc ON ptc.product_id = p.product_id JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON nft.table_id = ptc.category_id AND nft.table = 1 WHERE nft.nxc_formula_id = $id;");
            $products = array_merge($products, $query->rows);
            $query = $this->db->query("SELECT DISTINCT p.product_id, pd.name FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id JOIN `".DB_PREFIX."manufacturer` AS m JOIN `".DB_PREFIX."nxc_formula_table` AS nft ON p.manufacturer_id = m.manufacturer_id AND nft.table_id = m.manufacturer_id AND nft.table = 2 WHERE nft.nxc_formula_id = $id;");
            $products = array_merge($products, $query->rows);
            return array_map("unserialize", array_unique(array_map("serialize", $products)));
        }

        public function getTotalFormulas() {
            $count = $this->db->query("SELECT COUNT(`nxc_formula_id`) FROM `".DB_PREFIX."nxc_formula` WHERE 1;");
        }

        public function getFormulaTable($id) {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_formula_table` WHERE `nxc_formula_table_id` = $id;");
            return $query->rows[0];
        }

        public function getFormulaTables() {
            $query = $this->db->query("SELECT nft.nxc_formula_table_id, CONCAT(nf.name, '-> ', nf.formula) AS formula, nft.table, CONCAT(nr.name, ' #', nr.html_id) AS html_id, nft.status, nft.priority FROM `".DB_PREFIX."nxc_formula_table` AS nft JOIN `".DB_PREFIX."nxc_route` AS nr ON nft.nxc_route_id = nr.nxc_route_id JOIN `".DB_PREFIX."nxc_formula` AS nf ON nft.nxc_formula_id = nf.nxc_formula_id;");
            return $query->rows;
        }

        
        public function editFormula($data) {
            $this->db->query("UPDATE `".DB_PREFIX."nxc_formula` SET `formula`='".$data['formula']."', `status`='".$data['status']."', `value`='".$data['value'] ."' WHERE `nxc_formula_id` = ".$data['formula_id'].";");

            foreach($data['text'] as $lang => $text) {

                $this->db->query("INSERT INTO `".DB_PREFIX."nxc_language` (`nxc_formula_id`, `language_id`, `text`) SELECT ".$data['formula_id'].", $lang, '$text' FROM DUAL WHERE NOT EXISTS (SELECT * FROM `".DB_PREFIX."nxc_language` WHERE nxc_formula_id = ".$data['formula_id']." AND language_id = $lang);");
                $this->db->query("UPDATE `".DB_PREFIX."nxc_language` SET `text`='$text' WHERE nxc_formula_id = ".$data['formula_id']." AND language_id = $lang;");
            }
            
        }

        public function editFormulaTable($data) {
            $this->db->query("UPDATE `".DB_PREFIX."nxc_formula_table` SET `nxc_formula_id`='".(int)$data['nxc_formula_id']."',`nxc_route_id`='".(int)$data['nxc_route_id']."',`table_id`='".(int)$data['table_id']."',`priority`='".(int)$data['priority']."',`status`='".(int)$data['status']."',`table`='".(int)$data['table']."' WHERE `nxc_formula_table_id` = ".(int)$data['nxc_formula_table_id'].";");
        }
        
        public function delete($array) {
            $ids = implode("','", $array);
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_formula` WHERE `nxc_formula_id` IN ('".$ids."')");
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_formula_table` WHERE `nxc_formula_id` IN ('".$ids."')");
            
        }

        public function deleteTable($array, $formula_id, $table) {
            $ids = implode("','", $array);
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_formula_table` WHERE `table_id` IN ('$ids') AND `nxc_formula_id` = $formula_id AND `table` = $table");
        }

        public function deleteRoute($array) {
            $ids = implode("','", $array);
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_route` WHERE `nxc_route_id` IN ('".$ids."')");
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_formula_table` WHERE `nxc_route_id` IN ('".$ids."')");
        }

        public function deleteFormulaTable($array) {
            $ids = implode("','", $array);
            $this->db->query("DELETE FROM `".DB_PREFIX."nxc_formula_table` WHERE `nxc_formula_table_id` IN ('".$ids."')");
        }


        public function routeExist($route) {
            $query = $this->db->query("SELECT * FROM `".DB_PREFIX."nxc_added_route` WHERE `url`= '$route';");
            return $query->rows != null;
        }

        public function autoComplete($searchTerm) {
            $query = $this->db->query("SELECT pd.name, p.product_id AS id FROM `".DB_PREFIX."product` AS p JOIN `".DB_PREFIX."product_description` AS pd ON p.product_id = pd.product_id WHERE pd.name LIKE '%".$searchTerm."%' AND status = 1 ORDER BY name ASC;");
            return $query->rows;
        }
    }