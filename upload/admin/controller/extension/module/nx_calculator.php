<?php 
    class ControllerExtensionModuleNxCalculator extends Controller {
        private $error = array();
        public function index() {
            $this->load->language('extension/module/nx_calculator');
            $this->document->setTitle($this->language->get('heading_title'));
            
            $this->load->model('setting/setting');
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                
                $this->model_setting_setting->editSetting('module_nx_calculator', $this->request->post);
    
                $this->session->data['success'] = $this->language->get('text_success');
    
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            }

            $data['heading_title'] = $this->language->get('heading_title');
		
            $data['text_edit'] = $this->language->get('text_edit');
            $data['text_enabled'] = $this->language->get('text_enabled');
            $data['text_disabled'] = $this->language->get('text_disabled');
            
            $data['entry_status'] = $this->language->get('entry_status');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_module'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/nx_calculator', 'user_token=' . $this->session->data['user_token'], true)
            );
            
            $data['action'] = $this->url->link('extension/module/nx_calculator', 'user_token=' . $this->session->data['user_token'], true);

		    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
            
            if (isset($this->request->post['module_nx_calculator_status'])) {
                $data['module_nx_calculator_status'] = $this->request->post['module_nx_calculator_status'];
            } else {
                $data['module_nx_calculator_status'] = $this->config->get('module_nx_calculator_status');
            }
    
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
    
    
            $this->response->setOutput($this->load->view('extension/module/nx_calculator', $data));
        }

        public function formulas() {
            $this->load->language('extension/module/nx_calculator');
            $this->document->setTitle($this->language->get('heading_title'));

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
               
                $this->load->model('extension/module/nx_calculator');
                $this->model_extension_module_nx_calculator->addFormulaToTable($this->request->post);
                
            }
            
            $this->load->model('extension/module/nx_calculator');
            $data['routes'] = $this->model_extension_module_nx_calculator->getRoutes();
            
            $data['heading_title'] = $this->language->get('heading_title');
		

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_module'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/nx_calculator', 'user_token=' . $this->session->data['user_token'], true)
            );
            
            //action
            $data['route_action'] = $this->url->link('extension/module/nx_calculator/route', 'user_token='.$this->session->data['user_token'], true);
            $data['action'] = $this->url->link('extension/module/nx_calculator/formulas', 'user_token=' . $this->session->data['user_token'], true);

		    $data['cancel'] = $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
           
    
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $this->response->setOutput($this->load->view('extension/module/nxc_formulas', $data));
            
        }
        
        private function addJSToFile($url) {
           
            $this->load->model('extension/module/nx_calculator');
            $isexist = $this->model_extension_module_nx_calculator->routeExist($url);
            if(!$isexist) {
                $myfile = fopen("../catalog/view/theme/$url.twig", "a") or die("Unable to open file!");
                $txt = '
                <script>
                $( window ).on( \'load\', function() {
                    $.ajax({
                          url: \'index.php?route=extension/module/nx_calculator/calc&product_id={{ product_id }}\',
                          type: \'get\',
                          success: function(json) {
                            const data = JSON.parse(json);
                            let str = data.nxc.formula.replace(/[^-()\d/*+.]/g, \'\');
                            const calc = eval(str); 
                            $(`#${data.nxc.html_id}`).text(calc);
                          }
                      });
                  } );
                  </script>';
                fwrite($myfile, $txt);
                fclose($myfile);
                $this->model_extension_module_nx_calculator->addRouteToExistingRoute($url);
            }
        }

        public function list() {
            $this->load->language('extension/module/nx_calculator');
            $this->document->setTitle($this->language->get('heading_title'));


            
            $this->load->model('extension/module/nx_calculator');
            $data['routes'] = $this->model_extension_module_nx_calculator->getRoutes();
            
            $data['heading_title'] = $this->language->get('heading_title');
		

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_module'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/nx_calculator', 'user_token=' . $this->session->data['user_token'], true)
            );
            
            //action
            $data['route_action'] = $this->url->link('extension/module/nx_calculator/route', 'user_token='.$this->session->data['user_token'], true);
            $data['action'] = $this->url->link('extension/module/nx_calculator/formula', 'user_token=' . $this->session->data['user_token'], true);

		    $data['cancel'] = $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
           
    
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
    
    
            $this->response->setOutput($this->load->view('extension/module/nx_calculator_list', $data));
        }

        public function route() {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
               
                $this->load->model('extension/module/nx_calculator');
                $this->model_extension_module_nx_calculator->addRoute($this->request->post);
                $this->addJSToFile($this->request->post['url']);
            }

            $this->list();
        }

        public function formula() {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->load->model('extension/module/nx_calculator');
                $this->model_extension_module_nx_calculator->addFormula($this->request->post);
            }
            $this->list();
        }

        public function type() {
            $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            
            $json = array();
            if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
                if(isset($this->request->get['type'])) {
                    $type = strtolower($this->request->get['type']);
                    $json['success'] = $this->getList($type); 
                }
                else {
                    $json['error'] = 'empty query parameter';
                }
            }
            else {
                $json['error'] = 'Not found.';
            }

            $this->response->addHeader('Content-Type: application/json');
		    $this->response->setOutput(json_encode($json));
        }

        protected function getList($type) {
    
            if (isset($this->request->get['page'])) {
                $page = (int)$this->request->get['page'];
            } else {
                $page = 1;
            }
    
            $url = '';
    
            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
    
            $data['table'] = array();
    
            $filter_data = array(
                'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin')
            );
    
            if($type == 'category') {
                $this->load->model('catalog/category');
                $category_total = $this->model_catalog_category->getTotalCategories();
    
                $results = $this->model_catalog_category->getCategories($filter_data);
                $data['table_head'] = ['Id', 'Name', 'Action'];
                foreach ($results as $result) {
                    $data['table'][] = array(
                        'category_id' => $result['category_id'],
                        'name'        => $result['name'],
                        'edit'        => $this->url->link('catalog/category/edit', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url, true),
                        'delete'      => $this->url->link('catalog/category/delete', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url, true)
                    );
                }
            
                $pagination = new Pagination();
                $pagination->total = $category_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->url = $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
        
                $data['pagination'] = $pagination->render();
        
                $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
            }
            else if($type == 'product') {
                $this->load->model('catalog/product');
                $product_total = $this->model_catalog_product->getTotalProducts();
    
                $results = $this->model_catalog_product->getProducts($filter_data);
                $data['table_head'] = ['Id', 'Image', 'Name', 'Action'];
                foreach ($results as $result) {
                    $data['table'][] = array(
                        'product_id' => $result['product_id'],
                        'name'        => $result['name'],
                        'image'        => $result['image']
                    );
                }
            
                $pagination = new Pagination();
                $pagination->total = $product_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->url = $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
        
                $data['pagination'] = $pagination->render();
        
                $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
            }
            else  {
                $this->load->model('catalog/category');
                $category_total = $this->model_catalog_category->getTotalCategories();
    
                $results = $this->model_catalog_category->getCategories($filter_data);
                $data['table_head'] = ['Id', 'Name', 'Action'];
                foreach ($results as $result) {
                    $data['table'][] = array(
                        'category_id' => $result['category_id'],
                        'name'        => $result['name'],
                        'edit'        => $this->url->link('catalog/category/edit', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url, true),
                        'delete'      => $this->url->link('catalog/category/delete', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url, true)
                    );
                }
            
                $pagination = new Pagination();
                $pagination->total = $category_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->url = $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
        
                $data['pagination'] = $pagination->render();
        
                $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
            }
            return $data;
        }

        public function table() {
            $this->load->language('extension/module/nx_calculator');
            $this->document->setTitle($this->language->get('heading_title'));
            
            $data['form_types'] = array();
            $data['form_types'][] = array(
                'name' => 'Category',
                'href' => $this->url->link('extension/module/nx_calculator/type', 'user_token=' . $this->session->data['user_token'] . '&type=category', true)
            );
            $data['form_types'][] = array(
                'name' => 'Product',
                'href' => $this->url->link('extension/module/nx_calculator/type', 'user_token=' . $this->session->data['user_token'] . '&type=product', true)
            );
            $data['form_types'][] = array(
                'name' => 'Brand',
                'href' => $this->url->link('extension/module/nx_calculator/type', 'user_token=' . $this->session->data['user_token'] . '&type=brand', true)
            );
            
            $data['selected_type'] = $data['form_types'][0];
            
            $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );
    
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_formula'),
                'href' => $this->url->link('extension/module/nx_calculator/formulas', 'user_token=' . $this->session->data['user_token'], true)
            );
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'], true)
            );
            
    
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
    
    
            $this->response->setOutput($this->load->view('extension/module/nxc_table', $data));
        }

        public function install() {
            $this->load->model('user/user_group');

            $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."nxc_route`");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nxc_route` (
                `nxc_route_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(50) NOT NULL,
                `url` varchar(250) NOT NULL,
                `html_id` varchar(250),
                PRIMARY KEY (`nxc_route_id`))"
            );

            $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."nxc_added_route`");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nxc_added_route` (
                `nxc_added_id` int(11) NOT NULL AUTO_INCREMENT,
                `url` varchar(250) NOT NULL,
                UNIQUE KEY `idnew_table_UNIQUE` (`url`),
                PRIMARY KEY (`nxc_added_id`))"
            );


            $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."nxc_formula`");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nxc_formula` (
                `nxc_formula_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(200) NOT NULL,
                `formula` varchar(250) NOT NULL,
                `value` tinyint(1),
                `status` tinyint(1),
                PRIMARY KEY (`nxc_formula_id`))"
            );

            $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."nxc_formula_table`");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nxc_formula_table` (
                `nxc_formula_table_id` int(11) NOT NULL AUTO_INCREMENT,
                `nxc_formula_id` int(11) NOT NULL,
                `nxc_route_id` int(11) NOT NULL,
                `table_id` int(11) NOT NULL,
                `priority` int(11),
                `table` tinyint(1) NOT NULL,
                PRIMARY KEY (`nxc_formula_table_id`))"
            );

            // $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "nx_calculator`");
            // $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "nx_calc_category`");

            // $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nx_calculator` (
            //     `nx_calculator_id` int(11) NOT NULL AUTO_INCREMENT,
            //     `output_id` varchar(50),
            //     `name` varchar(50),
            //     `url` varchar(250),
            //     `formula` varchar(270),
            //     `status` tinyint(1) NOT NULL,
            //     PRIMARY KEY (`nx_calculator_id`))"
            // );

            // $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."nx_calc_category` (
            //     `nx_calc_category_id` int(11) NOT NULL AUTO_INCREMENT,
            //     `category_id` int(11) NOT NULL,
            //     `nx_calculator_id` int(11) NOT NULL,
            //     `exception` text,
            //     PRIMARY KEY (`nx_calc_category_id`),
            //     FOREIGN KEY (`nx_calculator_id`) REFERENCES `".DB_PREFIX."nx_calculator` (`nx_calculator_id`),
            //     FOREIGN KEY (`category_id`) REFERENCES `".DB_PREFIX."category` (`category_id`)
            // ) ");

            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/module/nx_calculator');
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/module/nx_calculator');
        }


        public function uninstall() {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "nx_calculator`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "nx_calc_category`");
        }


        public function validate() {
            if (!$this->user->hasPermission('modify', 'extension/module/nx_calculator')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
    
            return !$this->error;
        }


    }