<?php 
    class ControllerExtensionModuleNxCalculator extends Controller {
        public function calc() {
            if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
                $id = $this->request->get['product_id'];

                $this->load->model('catalog/product');
                $product = $this->model_catalog_product->getProduct($id);
                
                $this->load->model('extension/module/nx_calculator');
                $form = $this->model_extension_module_nx_calculator->getFormula($id);
                $route = $this->model_extension_module_nx_calculator->getRoute($id);
                if($form || $route){
                    $price = $form['value'] ? $product['price'] : $product['special'];
                    $replaced = str_replace('[value]', $price, $form['formula']);
                    $json['nxc'] = array('price' => $price, 'formula' => $replaced, 'html_id' => $route['html_id']);
                }
                else {
                    $json['nxc'] = 'not found';
                }
                $this->response->setOutput(json_encode($json));
            }
        }

        
    }