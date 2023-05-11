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
        $data['formulas'] = $this->model_extension_module_nx_calculator->getFormulas();
        $data['tables'] = [['id' => 0, 'name' => $this->language->get('table_product')], ['id' => 1, 'name' => $this->language->get('table_category')], ['id' => 2, 'name' => $this->language->get('table_brand')]];

        $data['autocomp_api'] = $this->url->link('admin/index.php?route=extension/module/nx_calculator/autocomplete', 'user_token='.$this->session->data['user_token'], true);
        $data['heading_title'] = $this->language->get('heading_title');
        $data['token'] = $this->session->data['user_token'];

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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_table'),
            'href' => $this->url->link('extension/module/nx_calculator/formulas', 'user_token=' . $this->session->data['user_token'], true)
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
    
    // private function addJSToFile($url) {
        
    //     $this->load->model('extension/module/nx_calculator');
    //     $isexist = $this->model_extension_module_nx_calculator->routeExist($url);
    //     if(!$isexist) {
    //         $myfile = fopen("$url.twig", "a") or die("Unable to open file!");
    //         $txt = '
    //         <script>
    //         $( window ).on( \'load\', function() {
    //             $.ajax({
    //                   url: \'index.php?route=extension/module/nx_calculator/calc&product_id={{ product_id }}&url='.$url.'\',
    //                   type: \'get\',
    //                   success: function(json) {
    //                     const data = JSON.parse(json);
                        
    //                     for(let i = 0; i < data.nxc.length; i++) {
    //                         let n = data.nxc[i];
                            
    //                         let str = n.formula.replace(/[^-()\d/*+.]/g, \'\');
    //                         const calc = eval(str);
                            
    //                         const num = (Math.round(calc * 100) / 100).toFixed(2)
    //                         $(`#${n.html_id}`).append(num);
    //                     }
    //                   }
    //               });
    //           } );
    //           </script>';
    //         fwrite($myfile, $txt);
    //         fclose($myfile);
    //         $this->model_extension_module_nx_calculator->addRouteToExistingRoute($url);
    //     }
    // }

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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_new'),
            'href' => $this->url->link('extension/module/nx_calculator/list', 'user_token=' . $this->session->data['user_token'], true)
        );
        //action
        $data['route_action'] = $this->url->link('extension/module/nx_calculator/route', 'user_token='.$this->session->data['user_token'], true);
        $data['action'] = $this->url->link('extension/module/nx_calculator/formula', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['calculator'] = $this->load->view('extension/module/nxc_element');
        
        $this->response->setOutput($this->load->view('extension/module/nx_calculator_list', $data));
    }

    public function route() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->load->model('extension/module/nx_calculator');
            $this->model_extension_module_nx_calculator->addRoute($this->request->post);
            //$this->addJSToFile($this->request->post['url']);
        }

        $this->list();
    }

    public function formula() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->request->post['formula'] = preg_replace('/undefined$/', '', $this->request->post['formula']);
            $this->load->model('extension/module/nx_calculator');
            $this->model_extension_module_nx_calculator->addFormula($this->request->post);
        }
        $this->list();
    }

    public function type() {
        
        $json = array();
        if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
            if(isset($this->request->get['type'])) {
                $this->load->model('extension/module/nx_calculator');
                $id = isset($this->request->get['formula_id']) ? $this->request->get['formula_id'] : -1;
                $query = $this->model_extension_module_nx_calculator->getTable($this->request->get['type'], $id);
                $json['table'] = $query;
                $json['success'] = true;
            }
            else {
                $json['error'] = 'empty query parameter';
                $json['success'] = false;
            }
        }
        else {
            $json['error'] = 'Not found.';
            $json['success'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit() {
        $this->load->language('extension/module/nx_calculator');
        $this->document->setTitle($this->language->get('heading_title'));

        
        
        $data['heading_title'] = $this->language->get('heading_title');
        $id = -1;
        $this->load->model('extension/module/nx_calculator');
        if(($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate() && isset($this->request->get['nxc_formula_id'])) {
            $id = $this->request->get['nxc_formula_id'];
        }
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
            
            if(isset($this->request->post['delete'])) {
                $id = $this->request->get['nxc_formula_id'];
                $this->model_extension_module_nx_calculator->deleteTable($this->request->post['selected'], $id, $this->request->post['table']);
            }
            else {
                $id = $this->request->post['formula_id'];
                $this->request->post['formula'] = preg_replace('/undefined$/', '', $this->request->post['formula']);
                $this->model_extension_module_nx_calculator->editFormula($this->request->post);
            }
            
        }

        
        $formula = $this->model_extension_module_nx_calculator->getFormula($id);
        $data['products'] = $this->model_extension_module_nx_calculator->getProducts($id);
        $data['formula'] = $formula;
        $data['calc'] = $formula['formula'];
        $data['faction'] = $this->url->link('extension/module/nx_calculator/edit', 'user_token='.$this->session->data['user_token'] . "&nxc_formula_id=$id", true);

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $data['token'] = $this->session->data['user_token'];
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_edit'),
            'href' => $this->url->link('extension/module/nx_calculator/edit', 'user_token=' . $this->session->data['user_token'], true)
        );
        //action
        
        $data['action'] = $this->url->link('extension/module/nx_calculator/formula', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('extension/module/nx_calculator/edit', 'user_token='.$this->session->data['user_token'].'&nxc_formula_id='.$formula['nxc_formula_id'], true);
        $data['cancel'] = $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        
        
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['calculator'] = $this->load->view('extension/module/nxc_element', $data);
        
        $this->response->setOutput($this->load->view('extension/module/nxc_edit', $data));
    }

    public function delete() {
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                
                $this->load->model('extension/module/nx_calculator');
                $query = $this->model_extension_module_nx_calculator->delete($this->request->post['selected']);
        }
        $this->table();
    }

    

    public function autocomplete() {
        $json = array();
        if(($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
            $term = $this->request->get['term'];

            $this->load->model('extension/module/nx_calculator');
            $query = $this->model_extension_module_nx_calculator->autoComplete($term);
            
            if(count($query) > 0){ 
                foreach($query as $row){ 
                    $data['id'] = $row['id']; 
                    $data['value'] = $row['name']; 
                    array_push($json, $data); 
                } 
            } 
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function table() {
        $this->load->language('extension/module/nx_calculator');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/nx_calculator');

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        

        $data['add'] = $this->url->link('extension/module/nx_calculator/list', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('extension/module/nx_calculator/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['rdelete'] = $this->url->link('extension/module/nx_calculator/deleteRoute', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['add_to_table'] = $this->url->link('extension/module/nx_calculator/formulas', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['forms'] = array();

            
        $filter_data = array(
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $forms_total = $this->model_extension_module_nx_calculator->getTotalFormulas();

        $results = $this->model_extension_module_nx_calculator->getFormulas($filter_data);

        foreach ($results as $result) {
            $data['forms'][] = array(
                'nxc_formula_id' => $result['nxc_formula_id'],
                'name'        => $result['name'],
                'formula'  => $result['formula'],
                'edit'        => $this->url->link('extension/module/nx_calculator/edit', 'user_token=' . $this->session->data['user_token'] . '&nxc_formula_id=' . $result['nxc_formula_id'] . $url, true),
                'delete'      => $this->url->link('extension/module/nx_calculator/delete', 'user_token=' . $this->session->data['user_token'] . '&nxc_formula_id=' . $result['nxc_formula_id'] . $url, true)
            );
        }

        $results = $this->model_extension_module_nx_calculator->getRoutes($filter_data);

        foreach ($results as $result) {
            $data['routes'][] = array(
                'nxc_route_id' => $result['nxc_route_id'],
                'name'        => $result['name'],
                'html_id'  => $result['html_id'],
                'delete'      => $this->url->link('extension/module/nx_calculator/deleteRoute', 'user_token=' . $this->session->data['user_token'] . '&nxc_route_id=' . $result['nxc_route_id'] . $url, true)
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $pagination = new Pagination();
        $pagination->total = $forms_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/nx_calculator/table', 'user_token=' . $this->session->data['user_token']. '&page={page}', true);
    
        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($forms_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($forms_total - $this->config->get('config_limit_admin'))) ? $forms_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $forms_total, ceil($forms_total / $this->config->get('config_limit_admin')));

    
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/nxc_table', $data));
    }

    public function deleteRoute() {
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->load->model('extension/module/nx_calculator');
            $query = $this->model_extension_module_nx_calculator->deleteRoute($this->request->post['selected']);
        }
        $this->table();
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
            `status` tinyint(1),
            `table` tinyint(1) NOT NULL,
            PRIMARY KEY (`nxc_formula_table_id`))"
        );

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