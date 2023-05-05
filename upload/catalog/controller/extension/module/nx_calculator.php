<?php 
    class ControllerExtensionModuleNxCalculator extends Controller {
        // public function calc() {
        //     if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
        //         $id = $this->request->get['product_id'];

        //         $this->load->model('catalog/product');
        //         $product = $this->model_catalog_product->getProduct($id);
                
        //         $this->load->model('extension/module/nx_calculator');
        //         $form = $this->model_extension_module_nx_calculator->getFormula($id);
        //         $route = $this->model_extension_module_nx_calculator->getRoute($id);
        //         if($form || $route){
        //             $price = $form['value'] ? $product['price'] : $product['special'];
                    
        //             $replaced = str_replace('[value]', $price, $form['formula']);
        //             $json['nxc'] = array('price' => $price, 'formula' => $replaced, 'html_id' => $route['html_id']);
        //         }
        //         else {
        //             $json['nxc'] = 'not found';
        //         }
        //         $this->response->setOutput(json_encode($json));
        //     }
        // }

        public function calc() {
            if($this->request->server['REQUEST_METHOD'] == 'GET'){
               
                if(isset($this->request->get['product_id']) && isset($this->request->get['url'])) {
                    $id = $this->request->get['product_id'];
                    $url = $this->request->get['url'];
                }
                else {
                    $this->response->setOutput(json_encode(['success' => false, 'message' => 'product_id or url not found']));
                    return;
                }

                $this->load->model('extension/module/nx_calculator');
                $form = $this->model_extension_module_nx_calculator->getFormulaAdv($id, $url);

                $this->load->model('catalog/product');
                $product = $this->model_catalog_product->getProduct($id);

                if($form ){
                    
                    foreach($form as $f) {
                        $price = $f['value'] ? (isset($product['special']) ? $product['special'] : $product['price']) : $product['price'];
                        $replaced = str_replace('[value]', $price, $f['formula']);
                        $json['nxc'][] = array('price' => $price, 'formula' => $replaced, 'html_id' => $f['html_id']);
                    }
                   $json['success'] = true;
                    
                    $this->response->setOutput(json_encode($json));
                }
                else {
                    $this->response->setOutput(json_encode(['success' => false, 'message' => 'formula not found']));
                }
            }
        }
        
    }