<?php 
    class ControllerExtensionModuleNxCalculator extends Controller {
        
        private function arrayContain($array, $value) {
            foreach($array as $a) {
                if(isset($a['html_id']) && $a['html_id'] === $value) {
                   return true; // do something
                }
            }
            return false;
        }

        public function calc() {
            if($this->request->server['REQUEST_METHOD'] == 'GET'){
               
                if(isset($this->request->get['product_id'])) {
                    $id = $this->request->get['product_id'];
                }
                else { 
                    $this->response->setOutput(json_encode(['success' => false, 'message' => 'product_id or url not found']));
                    return;
                }

                $this->load->model('extension/module/nx_calculator');
                $form = $this->model_extension_module_nx_calculator->getFormulaAdv($id);
               
                $this->load->model('catalog/product');
                $product = $this->model_catalog_product->getProduct($id);

                if($form ){
                    $json['nxc'] = array();
                    foreach($form as $f) {
                        if(!$this->arrayContain($json['nxc'], $f['html_id'])) {
                            $price = $f['value'] ? (isset($product['special']) ? $product['special'] : $product['price']) : $product['price'];
                            $replaced = str_replace('[value]', $price, $f['formula']);
                            $json['nxc'][] = array('price' => $price, 'formula' => $replaced, 'html_id' => $f['html_id'], 'text' => $f['text']);
                        }
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