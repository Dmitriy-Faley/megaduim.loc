<?php
class ControllerApiProduct extends Controller {

	protected $use_table_seo_url = true ;
	
	public function __construct( $registry ) {
		parent::__construct( $registry );
		$this->use_table_seo_url = version_compare(VERSION,'3.0','>=') ? true : true ;
	}

	public function proverka() {
		
		$this->load->language('api/proverka');

		$json = array();
      
      //if (!isset($this->session->data['api_id'])) {
      
		//	   $json['error'] = $this->language->get('error_permission');
         
      //} else {
			   $json['success'] = "Token correct" ;
      //}
      
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  
  
  public function otkl_tovar() {
		
		$this->load->language('api/otkl_tovar');

		$json = array();

      $query = $this->db->query( "UPDATE `".DB_PREFIX."product` set status = 0" );
      
			$json['success'] = "otkl_tovar" ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  
   public function vkl_tovar() {
		
		$this->load->language('api/vkl_tovar');

		$json = array();

      $query = $this->db->query( "UPDATE `".DB_PREFIX."product` set status = 1" );
      
			$json['success'] = "vkl_tovar" ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  
  
  
    
  public function clear_casche() {
  
  	$this->load->language('api/proverka');

		$json = array();


    $files = glob(DIR_CACHE.'*'); // get all file names
    foreach($files as $file){ // iterate files
      if(is_file($file))
        unlink($file); // delete file
    }


		$json['success'] = "clear casche" ;
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  
  
  }

	
	
	public function get_param() {
		
		$this->load->language('api/get_param');

		$json = array();


		
	
			$json['success'] = ini_get('upload_max_filesize') ;

			//$json['memory_limit'] = ini_get("memory_limit") ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}

	
	public function get_group_customers() {
		
		$this->load->language('api/get_group_customers');

		$json = array();


		
			$query = $this->db->query( "SELECT customer_group_id, name FROM `".DB_PREFIX."customer_group_description`" );
	
			$json['success'] = $query->rows ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	

	public function get_status_order() {
		
		$this->load->language('api/get_status_order');

		$json = array();


		
			$query = $this->db->query( "SELECT * FROM `".DB_PREFIX."stock_status`" );
	
			$json['success'] = $query->rows ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	public function get_status() {
		
		$this->load->language('api/get_status');

		$json = array();


		
			$query = $this->db->query( "SELECT * FROM `".DB_PREFIX."order_status`" );
	
			$json['success'] = $query->rows ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  
  
  
  public function dubl_del() {
		
		$this->load->language('api/dubl_del');

		$json = array();


		
			$vozvrat_json = array();
			
			$image_f=  file_get_contents('php://input');

	$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
	file_put_contents($nameZip, $image_f);
			

			
	$zipArc = zip_open($nameZip);
			
	if (is_resource($zipArc)) {
						
	
			$first_product_image1 = true;
      $first_product_image2 = true;
      $first_product_image3 = true;
      $first_product_image4 = true;
      $first_product_image5 = true;
      $first_product_image6 = true;
      $first_product_image7 = true;
      $first_product_image8 = true;
      $first_product_image9 = true;
      $first_product_image10 = true;
      $first_product_image11 = true;
      $first_product_image12 = true;
      $first_product_image13 = true;
      $first_product_image14 = true;
      
      
			$sql_product_image1 = "DELETE FROM `".DB_PREFIX."product_image` WHERE product_id in (";
      $sql_product_image2 = "DELETE FROM `".DB_PREFIX."product` WHERE product_id in (";
      $sql_product_image3 = "DELETE FROM `".DB_PREFIX."product_attribute` WHERE product_id in (";
      $sql_product_image4 = "DELETE FROM `".DB_PREFIX."product_description` WHERE product_id in (";
      $sql_product_image5 = "DELETE FROM `".DB_PREFIX."product_discount` WHERE product_id in (";
      $sql_product_image6 = "DELETE FROM `".DB_PREFIX."product_filter` WHERE product_id in (";
      
      $sql_product_image7 = "DELETE FROM `".DB_PREFIX."product_option` WHERE product_id in (";
      $sql_product_image8 = "DELETE FROM `".DB_PREFIX."product_option_value` WHERE product_id in (";
      $sql_product_image9 = "DELETE FROM `".DB_PREFIX."product_related` WHERE product_id in (";
      $sql_product_image10 = "DELETE FROM `".DB_PREFIX."product_recurring` WHERE product_id in (";
			
      $sql_product_image11 = "DELETE FROM `".DB_PREFIX."product_special` WHERE product_id in (";
      $sql_product_image12 = "DELETE FROM `".DB_PREFIX."product_to_category` WHERE product_id in (";
      $sql_product_image13 = "DELETE FROM `".DB_PREFIX."product_to_store` WHERE product_id in (";
      $sql_product_image14 = "DELETE FROM `".DB_PREFIX."product_to_layout` WHERE product_id in (";
      
      						  	
		while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			
			
			$options_array= json_decode($dump);
			

			foreach ($options_array as $product_id) {
									        
        $sql_product_image1 .= ($first_product_image1 ) ? "" : ",";
				$sql_product_image1 .= " $product_id ";
        
        $sql_product_image2 .= ($first_product_image2 ) ? "" : ",";
				$sql_product_image2 .= " $product_id ";
        
        $sql_product_image3 .= ($first_product_image3 ) ? "" : ",";
				$sql_product_image3 .= " $product_id ";
        
        $sql_product_image4 .= ($first_product_image4 ) ? "" : ",";
				$sql_product_image4 .= " $product_id ";
        
        $sql_product_image5 .= ($first_product_image5 ) ? "" : ",";
				$sql_product_image5 .= " $product_id ";
        
        $sql_product_image6 .= ($first_product_image6 ) ? "" : ",";
				$sql_product_image6 .= " $product_id ";
        
        $sql_product_image7 .= ($first_product_image7 ) ? "" : ",";
				$sql_product_image7 .= " $product_id ";
        
        $sql_product_image8 .= ($first_product_image8 ) ? "" : ",";
				$sql_product_image8 .= " $product_id ";
        
        $sql_product_image9 .= ($first_product_image9 ) ? "" : ",";
				$sql_product_image9 .= " $product_id ";
        
        $sql_product_image10 .= ($first_product_image10 ) ? "" : ",";
				$sql_product_image10 .= " $product_id ";
        
        $sql_product_image11 .= ($first_product_image11 ) ? "" : ",";
				$sql_product_image11 .= " $product_id ";
        
        $sql_product_image12 .= ($first_product_image12 ) ? "" : ",";
				$sql_product_image12 .= " $product_id ";
        
        $sql_product_image13 .= ($first_product_image13 ) ? "" : ",";
				$sql_product_image13 .= " $product_id ";
        
        $sql_product_image14 .= ($first_product_image14 ) ? "" : ",";
				$sql_product_image14 .= " $product_id ";
        
        $first_product_image1 = false;
        $first_product_image2 = false;
        $first_product_image3 = false;
        $first_product_image4 = false;
        $first_product_image5 = false;
        $first_product_image6 = false;
        $first_product_image7 = false;
        $first_product_image8 = false;
        $first_product_image9 = false;
        $first_product_image10 = false;
        $first_product_image11 = false;
        $first_product_image12 = false;
        $first_product_image13 = false;
        $first_product_image14 = false;
					
			}		
						
			}}	 
			
			if (!$first_product_image ) {								
												
				$sql_product_image1 .=");";
				$this->db->query($sql_product_image1 );
        
        $sql_product_image2 .=");";
				$this->db->query($sql_product_image2 );
        
        $sql_product_image3 .=");";
				$this->db->query($sql_product_image3 );
        
        $sql_product_image4 .=");";
				$this->db->query($sql_product_image4 );
        
        $sql_product_image5 .=");";
				$this->db->query($sql_product_image5 );
        
        $sql_product_image6 .=");";
				$this->db->query($sql_product_image6);
        
        $sql_product_image7 .=");";
				$this->db->query($sql_product_image7 );
        
        $sql_product_image8 .=");";
				$this->db->query($sql_product_image8);
        
        $sql_product_image9 .=");";
				$this->db->query($sql_product_image9 );
        
        $sql_product_image10 .=");";
				$this->db->query($sql_product_image10 );
        
        $sql_product_image11 .=");";
				$this->db->query($sql_product_image11 );
        
        $sql_product_image12 .=");";
				$this->db->query($sql_product_image12 );
        
        $sql_product_image13 .=");";
				$this->db->query($sql_product_image13 );
        
        $sql_product_image14 .=");";
				$this->db->query($sql_product_image14 );
        
        
			}
			

			zip_close($zipArc);
			unlink($nameZip);
			$json['success'] = 'dubl del' ;
		}else{
				$json['error']   = 'zip not archive' ;				
		}	
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  

  
  
  
  public function images_del() {
		
		$this->load->language('api/images_del');

		$json = array();


		
			$vozvrat_json = array();
			
			$image_f=  file_get_contents('php://input');

	$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
	file_put_contents($nameZip, $image_f);
			

			
	$zipArc = zip_open($nameZip);
			
	if (is_resource($zipArc)) {
						
	
			$first_product_image = true;
			$sql_product_image = "DELETE FROM `".DB_PREFIX."product_image` WHERE product_image_id in (";
									  	
		while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			
			
			$options_array= json_decode($dump);
			

			foreach ($options_array as $product_image_id) {
									
				$sql_product_image .= ($first_product_image ) ? "" : ",";
				$sql_product_image .= " $product_image_id ";
				$first_product_image = false;
					
			}		
						
			}}	 
			
			if (!$first_product_image ) {								
												
				$sql_product_image .=");";
				$this->db->query($sql_product_image );
			}
			

			zip_close($zipArc);
			unlink($nameZip);
			$json['success'] = 'udal images del' ;
		}else{
				$json['error']   = 'zip not archive' ;				
		}	
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
  
  
	public function images_add() {
		
		$this->load->language('api/images_add');

		$json = array();


		
			$vozvrat_json = array();
			
			$image_f=  file_get_contents('php://input');

	$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
	file_put_contents($nameZip, $image_f);
			

			
	$zipArc = zip_open($nameZip);
			
	if (is_resource($zipArc)) {
			
			$language_id= $this->getDefaultLanguageId();
	
			//$first_product_image = true;
			//$sql_product_image = "INSERT INTO `".DB_PREFIX."product_image` (`product_image_id`, `product_id`,`image`) VALUES ";
			
			$first_download = true;			
			$sql_download = "INSERT INTO `".DB_PREFIX."download` (`download_id`, `filename`, `mask`, `date_added`) VALUES ";
			
      $first_delete_product = true;
			$sql_first_delete_product = "DELETE FROM `".DB_PREFIX."product_image` WHERE product_id IN (";
      
      
			$first_download_description = true;			
			$sql_download_description = "INSERT INTO `".DB_PREFIX."download_description` (`download_id`, `language_id`, `name`) VALUES ";
			
			$first_product_to_download = true;			
			$sql_product_to_download = "INSERT INTO `".DB_PREFIX."product_to_download` (`product_id`, `download_id`) VALUES ";
	
	
			$first_delete_path = true;
			$sql_first_delete_path = "DELETE FROM `".DB_PREFIX."product_to_download` WHERE download_id IN (";
	
			$sql = "Select max(`product_image_id`) as `product_image_id` from `".DB_PREFIX."product_image`;";
			$result = $this->db->query( $sql );
			
			$product_image_id_max = 0;
			foreach ($result->rows as $row) {				
				$product_image_id_max = (int)$row['product_image_id'];				
			}
			
			$sql = "Select max(`download_id`) as `download_id` from `".DB_PREFIX."download`;";
			$result = $this->db->query( $sql );
			$download_id_max = 0;
			foreach ($result->rows as $row) {				
				$download_id_max = (int)$row['download_id'];				
			}
		
    
    $product_array = array();
    	  	
		while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			
			
			$options_array= json_decode($dump);
			
			

			
			foreach ($options_array as $option) {
			
				$name= $option->{'name'};
				$product_image_id= $option->{'product_image_id'};
				$product_id= $option->{'product_id'};
				$product_to_image= $option->{'product_to_image'};
				
				$mask = urldecode($this->db->escape($option->{'mask'}));
				$image= urldecode($this->db->escape($option->{'name'}));
				$namef= urldecode($option->{'namef'});
				$download= $option->{'download'};
				$language_id_u= $option->{'language_id'};
			
			
         if (!isset($product_array[$product_id])) {
        
          $sql_first_delete_product .= ($first_delete_product ) ? "" : ",";		
				  $sql_first_delete_product .= " $product_id ";	
				  $first_delete_product = false;
          
          $product_array[$product_id] = true;
          
        }
      
      
				if ($download== 0) {
					if ($product_to_image== 1) {
						if ($product_image_id== 0) {
						
							$product_image_id_max = $product_image_id_max + 1;				
							$product_image_id= $product_image_id_max ;
							$insert = 1;		
																	
						}else {														
							$insert = 0;
							
							//$sql_first_delete_path .= ($first_delete_path ) ? "" : ",";
							//$sql_second_delete_path .= ($first_delete_path ) ? "" : ",";
							
							//$sql_first_delete_path .= " $category_id ";
							//$sql_second_delete_path .= " $category_id ";	
							//$first_delete_path = false;
																								
						};
					
						/* $sql_product_image .= ($first_product_image ) ? "" : ",";
						$sql_product_image .= " ( $product_image_id,$product_id, '$image') ";
						$first_product_image = false; */
					

					
						
						
					
						$vozvrat_json[$option->{'ref'}]= $product_image_id;
					}else {
          
						 $vozvrat_json[$option->{'ref'}]= -10;

					}	
				}else {
						if ($product_image_id== 0) {
						
							$download_id_max = $download_id_max + 1;				
							$product_image_id = $download_id_max ;
							$insert = 1;		
																	
						}else {														
							$insert = 0;
							
							$sql_first_delete_path .= ($first_delete_path ) ? "" : ",";
							//$sql_second_delete_path .= ($first_delete_path ) ? "" : ",";
							
							$sql_first_delete_path .= " $product_image_id";
							//$sql_second_delete_path .= " $category_id ";	
							$first_delete_path = false;
																								
						};
					
						$sql_download .= ($first_download ) ? "" : ",";
						$sql_download .= " ( $product_image_id,'$name', '$mask',NOW()) ";
						$first_download = false;
						
						$sql_download_description .= ($first_download_description ) ? "" : ",";
						$sql_download_description .= " ( $product_image_id,$language_id, '$namef') ";
						$first_download_description = false;
					
						$sql_product_to_download.= ($first_product_to_download ) ? "" : ",";
						$sql_product_to_download.= " ( $product_id,$product_image_id) ";
						$first_product_to_download = false;
					
						
						
					
						$vozvrat_json[$option->{'ref'}]= $product_image_id;
					
				} 
			}
			}}
			if (!$first_delete_path ) {
				$sql_first_delete_path .=");";
				$this->db->query($sql_first_delete_path );
			}
			
			if (!$first_download ) {								
			
				$sql_download .=" ON DUPLICATE KEY UPDATE  ";
				$sql_download .= "`filename`= VALUES(`filename`),";
				$sql_download .= "`date_added`= NOW(),";
				$sql_download .= "`mask`= VALUES(`mask`)";
												
				$sql_download .=";";
				$this->db->query($sql_download );
				
				$sql_download_description .=" ON DUPLICATE KEY UPDATE  ";
				$sql_download_description .= "`language_id`= VALUES(`language_id`),";
				$sql_download_description .= "`name`= VALUES(`name`)";
												
				$sql_download_description .=";";
				$this->db->query($sql_download_description );
				
				
				//$sql_product_to_download.=" ON DUPLICATE KEY UPDATE  ";
				//$sql_product_to_download.= "`download_id `= VALUES(`download_id `)";
												
				$sql_product_to_download.=";";
				$this->db->query($sql_product_to_download);
			}
			
      if (!$first_delete_product ) {
				$sql_first_delete_product .=");";
				$this->db->query($sql_first_delete_product );
			}
      /*
      if (!$first_product_image ) {								
			
				$sql_product_image .=" ON DUPLICATE KEY UPDATE  ";
				$sql_product_image .= "`product_id`= VALUES(`product_id`),";
				$sql_product_image .= "`image`= VALUES(`image`)";
												
				$sql_product_image .=";";
				$this->db->query($sql_product_image );
			}
			*/
			
			  	

			zip_close($zipArc);
			unlink($nameZip);
			$json['success'] = $vozvrat_json ;
		}else{
				$json['error']   = 'zip not archive' ;				
		}	
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	public function images_go() {
		
		$this->load->language('api/images_go');

		$json = array();


		
			//$json = '';
			$image_f=  file_get_contents('php://input');

			$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
			file_put_contents($nameZip, $image_f);
			

			
			$zipArc = zip_open($nameZip);
			
      $dirname_array = array();
      
      
			if (is_resource($zipArc)) {
			

			  	
			  	while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			  			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			  			
			  			$options_array = json_decode(htmlspecialchars_decode($dump));
				 				
						foreach ($options_array as $option) {
						
							$namef= $option->{'namef'};
			
							$image_f = base64_decode($option->{'imagef'});
						
              $namef_with_dir = DIR_IMAGE.'catalog/'.$namef;
            
              $dirname_namef = dirname($namef_with_dir);
              
              if (!isset($dirname_array[$dirname_namef])) {
              
                if(!file_exists($dirname_namef)){
                  mkdir($dirname_namef, 0777, true);
                }
                $dirname_array[$dirname_namef] = true;
                
              }	
							
							file_put_contents($namef_with_dir, $image_f);
						}	
			  			

			  		}
			  	}
			  	

				zip_close($zipArc);
				unlink($nameZip);
				
				$json['success'] = 'images upload' ;
			}else{
				$json['error']   = 'zip not archive' ;				
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	
	public function deleteAkcii() {
		
		$this->load->language('api/deleteAkcii');

		$json = array();


		
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_discount`;";
			$this->db->query( $sql );
			
			
			
				
			$json['success'] = 'Akcii udaleni' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	
	public function deleteimg() {
		
		$this->load->language('api/deleteimg');

		$json = array();


		
			$sql = "TRUNCATE TABLE `".DB_PREFIX."download`;";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."download_description`;";
			$this->db->query( $sql );
			
			$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = DATABASE() AND table_name  = '".DB_PREFIX."download_report';";
			$result = $this->db->query($query);
			 
			if (count($result->rows)==1) { 
   				$sql = "TRUNCATE TABLE `".DB_PREFIX."download_report`;";
				$this->db->query( $sql ); 
			}
			
			
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_download`;";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_image`;";
			$this->db->query( $sql );
			
	
			$json['success'] = 'files udaleni' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	
	
	
	public function deleteSpecials() {
		
		$this->load->language('api/deleteSpecials');

		$json = array();


		
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_special`;";
			$this->db->query( $sql );
			
			
			
				
			$json['success'] = 'Specials udaleni' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	
	
	public function update_price() {
		$this->load->language('api/update_price');
     
    $type_option  = $this->request->get['type_option'];
     
			$json = array();


		
			$image_f=  file_get_contents('php://input');

			$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
			file_put_contents($nameZip, $image_f);
			
      $option_vid = array();

			$option_id= 0;
			
			$zipArc = zip_open($nameZip);
			
			if (is_resource($zipArc)) {
			
				$first_product = true;
				$sql_product = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`price`,`stock_status_id`,`date_modified`,`date_available`,`status`) VALUES ";
				
				$first_product_option_value = true;
        $first_product_option_value0 = true; 
				$sql_product_option_value0 = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix`) VALUES ";
        $sql_product_option_value = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_value_id`,`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix`) VALUES ";	
			  
        $first_del_product_option_value= true;
			  $sql_del_product_option_value = "DELETE FROM `".DB_PREFIX."product_option_value` WHERE product_id IN (";
        
        $first_delete_product_option = true;
			  $sql_delete_product_option = "DELETE FROM `".DB_PREFIX."product_option` WHERE product_id IN (";
        
        $first_product_option = true; 
			  $sql_product_option = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`,`product_id`,`option_id`,`value`,`required`) VALUES ";
        
        	
			  	$sql_delete_product_special= "DELETE FROM `".DB_PREFIX."product_special` WHERE product_id IN ("; 
				$first_delete_product_special= true;

				$sql_product_special  = "INSERT INTO `".DB_PREFIX."product_special` (`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end`) VALUES "; 		
											
				$first_product_special = true;
				
				$sql_delete_product_discount= "DELETE FROM `".DB_PREFIX."product_discount` WHERE product_id IN ("; 
				$first_delete_product_discount= true;

				$sql_product_discount  = "INSERT INTO `".DB_PREFIX."product_discount` (`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end`) VALUES "; 		
											
				$first_product_discount = true;
			  
        $ProductOption= $this->getProductOption();
        $ProductOptionValue= $this->getProductOptionValue();
        

			

        
        $sql = "Select max(`product_option_id`) as `product_option_id` from `".DB_PREFIX."product_option`;";
			  $result = $this->db->query( $sql );
			  $product_option_id= 0;
			
			  foreach ($result->rows as $row) {				
				 $product_option_id= (int)$row['product_option_id'];
				 $product_option_id++;				
			 }
      
        	
			 while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			  			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			
			$products_id_array = json_decode($dump);
		
				
		

		
			foreach ($products_id_array as $parent) {
			
				$product_id= $parent->{'product_id'};
				$quantity= $parent->{'quantity'};
				$price= $parent->{'price'};
				$stock_status_id= $parent->{'stock_status_id'};
        $status= $parent->{'status'};
        $date_available= $parent->{'date_available'};		
			
			
				$sql_product .= ($first_product ) ? "" : ",";
				$sql_product .= " ( $product_id, $quantity, $price, $stock_status_id, NOW(),'$date_available',$status) ";
				$first_product  = false;
				
				$options = $parent->{'options'};

				
				if (empty($parent->{'specials'})) {
					$specials_empty = true;
				}else{ 
					$specials_empty = false;
					$specials = $parent->{'specials'};
				};
				
				if (empty($parent->{'akcii'})) {
					$akcii_empty = true;
				}else{ 
					$akcii_empty = false;
					$akcii = $parent->{'akcii'};
				};
				
        //if (!empty($type_option)) {            
          if (count($options) == 0) {
          
            $sql_del_product_option_value .= ($first_del_product_option_value) ? "" : ",";
    					
    				$sql_del_product_option_value .= " $product_id ";
    					
    				$first_del_product_option_value= false;
            

          //} else {
          
           $sql_delete_product_option .= ($first_delete_product_option ) ? "" : ",";
  				 $sql_delete_product_option .= " $product_id ";
  				 $first_delete_product_option = false;
          
            
          //}            
        }
        
        
        
        
         
				foreach ($options as $option) {
			
               $option_id= $option->{'option_id'};
               
               if (isset($ProductOption[$product_id][$option_id]) ) {
               
                 $option_vid[$product_id][$option_id] = $ProductOption[$product_id][$option_id];
               
               }
               
               if ((!isset($ProductOption[$product_id][$option_id])) and (!isset($option_vid[$product_id][$option_id]))) {
  						                                                    
        							$sql_product_option .= ($first_product_option ) ? "" : ","; 
        							$sql_product_option .= " ($product_option_id,$product_id,$option_id,'',1) ";
        							$first_product_option = false;
        							
        							$product_option_id_t = $product_option_id;
        							
        							$product_option_id++;
                    
                      $option_vid[$product_id][$option_id] = $product_option_id_t;
                						
  						} else {
  						
  							       $product_option_id_t = $option_vid[$product_id][$option_id];
  						}
      

      
							$option_value_id= $option->{'option_value_id'};
							$quantity= $option->{'quantity'};
							$subtract= $option->{'subtract'};
							$price= $option->{'price'};
							$price_prefix= $option->{'price_prefix'};
							$points= $option->{'points'};
							$points_prefix= $option->{'points_prefix'};
							$weight= $option->{'weight'};
							$weight_prefix= $option->{'weight_prefix'};
              
		
						if (isset($ProductOptionValue[$product_id][$option_id][$option_value_id])) {
                
                
                    $product_option_value_id = $ProductOptionValue[$product_id][$option_id][$option_value_id];
                    
                    $sql_product_option_value .= ($first_product_option_value ) ? "" : ",";
                    $sql_product_option_value .= "  ($product_option_value_id,$product_option_id_t, $product_id, $option_id, $option_value_id ,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix', $weight,'$weight_prefix') ";
							      $first_product_option_value = false;
                
                
              }else{
              
                    $sql_product_option_value0 .= ($first_product_option_value0 ) ? "" : ",";

							      $sql_product_option_value0 .= "  ($product_option_id_t, $product_id, $option_id, $option_value_id ,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix', $weight,'$weight_prefix') ";
                    $first_product_option_value0 = false;
              
              }
              
              
              
              
 
  
				}
				
        
        $sql_delete_product_special .= ($first_delete_product_special ) ? "" : ",";
				$sql_delete_product_special .= " $product_id ";
				$first_delete_product_special= false;
        
        
				if (!$specials_empty) {
				
					

					
					if (count($specials) == 0) {
					} else {
						foreach ($specials as $special) {
			
							$customer_group_id= $special->{'customer_group_id'};
							$priority= $special->{'priority'};
							$date_start= $special->{'date_start'};
							$price= $special->{'price'};
							$date_end= $special->{'date_end'};

		
							$sql_product_special .= ($first_product_special ) ? "" : ",";
					 
							$sql_product_special .= "  ($product_id, $customer_group_id,  $priority, $price,'$date_start','$date_end') ";
							
							

							$first_product_special = false;
						}
					}
				}
				
        $sql_delete_product_discount .= ($first_delete_product_discount ) ? "" : ",";
				$sql_delete_product_discount .= " $product_id ";
				$first_delete_product_discount = false;

        if (!$akcii_empty) {
				
					

					
					if (count($akcii) == 0) {
					} else {
						foreach ($akcii as $special) {
			
							$customer_group_id= $special->{'customer_group_id'};
							$priority= $special->{'priority'};
							$date_start= $special->{'date_start'};
							$price= $special->{'price'};
							$date_end= $special->{'date_end'};
							$quantity= $special->{'quantity'};

		
							$sql_product_discount .= ($first_product_discount ) ? "" : ",";
					 
							$sql_product_discount .= "  ($product_id, $customer_group_id, $quantity,  $priority, $price,'$date_start','$date_end') ";
							
							

							$first_product_discount= false;
						}
					}
				}//akcii
											 
			}
			
			}}
			
			if (!$first_delete_product_special ) {
			
				$sql_delete_product_special .=");";
				$this->db->query($sql_delete_product_special );
				
				$sql = "Select max(`product_special_id`) as `product_special_id` from `".DB_PREFIX."product_special`;";
				$result = $this->db->query( $sql );
				$product_special_id= 0;
				
				foreach ($result->rows as $row) {				
					$product_special_id= (int)$row['product_special_id'];
					$product_special_id++;
									
				}
				$sql = "ALTER TABLE `".DB_PREFIX."product_special` AUTO_INCREMENT = $product_special_id ;";
				$this->db->query($sql);
			}
			
			if (!$first_product_special ) {				
							
				$sql_product_special .=";";
				$this->db->query($sql_product_special );				
			}
			
			if (!$first_delete_product_discount ) {
			
				$sql_delete_product_discount .=");";
				$this->db->query($sql_delete_product_discount );
				
				$sql = "Select max(`product_discount_id`) as `product_discount_id` from `".DB_PREFIX."product_discount`;";
				$result = $this->db->query( $sql );
				$product_discount_id= 0;
				
				foreach ($result->rows as $row) {				
					$product_discount_id= (int)$row['product_discount_id'];
					$product_discount_id++;
									
				}
				$sql = "ALTER TABLE `".DB_PREFIX."product_discount` AUTO_INCREMENT = $product_discount_id;";
				$this->db->query($sql);
			}
			
			if (!$first_product_discount ) {				
							
				$sql_product_discount .=";";
				$this->db->query($sql_product_discount );				
			}
			
			if (!$first_product ) {
			
				$sql_product .=" ON DUPLICATE KEY UPDATE  ";
				$sql_product .= "`quantity`= VALUES(`quantity`),";
				$sql_product .= "`price`= VALUES(`price`),";
				$sql_product .= "`stock_status_id`= VALUES(`stock_status_id`),";
				$sql_product .= "`date_modified`= NOW(),";
        $sql_product .= "`date_available`= VALUES(`date_available`),";
        $sql_product .= "`status`= VALUES(`status`)";

				$sql_product .=";";
				$this->db->query($sql_product);
			}
			

      
      if (!$first_delete_product_option) {
			
				$sql_delete_product_option .=");";
				$this->db->query($sql_delete_product_option );
			}
      
      
      if (!$first_product_option ) {
			
				$sql_product_option .=";";
				$this->db->query($sql_product_option );
			}
      
      if (!$first_del_product_option_value) {
			
				$sql_del_product_option_value .=");";
				$this->db->query($sql_del_product_option_value );
        

        
        $sqlproduct_option_value_id = "Select max(`product_option_value_id`) as `product_option_value_id` from `".DB_PREFIX."product_option_value`;";
				$resultproduct_option_value = $this->db->query( $sqlproduct_option_value_id );
				$product_option_idproduct_option_value= 1;
			
				foreach ($resultproduct_option_value ->rows as $row) {				
					$product_option_idproduct_option_value= (int)$row['product_option_value_id'];
					$product_option_idproduct_option_value++;				
				}
				$sqlproduct_option_value_id = "ALTER TABLE `".DB_PREFIX."product_option_value` AUTO_INCREMENT=$product_option_idproduct_option_value;";
				$this->db->query( $sqlproduct_option_value_id );
        
        
        
			}
      

			if (!$first_product_option_value ) {
			
				$sql_product_option_value .=" ON DUPLICATE KEY UPDATE  ";
				$sql_product_option_value .= "`quantity`= VALUES(`quantity`),";
				$sql_product_option_value .= "`subtract`= VALUES(`subtract`),";
				$sql_product_option_value .= "`price`= VALUES(`price`),";
				$sql_product_option_value .= "`price_prefix`= VALUES(`price_prefix`),";
				$sql_product_option_value .= "`points`= VALUES(`points`),";
				$sql_product_option_value .= "`points_prefix`= VALUES(`points_prefix`),";
				$sql_product_option_value .= "`weight`= VALUES(`weight`),";				
				$sql_product_option_value .= "`weight_prefix`= VALUES(`weight_prefix`)";
				$sql_product_option_value .=";";
				$this->db->query($sql_product_option_value);
			}
			
      if (!$first_product_option_value0 ) {
			
				$sql_product_option_value0 .=";";
				$this->db->query($sql_product_option_value0);
			}

			
			
			zip_close($zipArc);
			unlink($nameZip);
			$json['success'] = 'update_price complete' ;
		}else{
				$json['error']   = 'zip not archive' ;				
		}	
			
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
	}
	public function update_order_status() {
		$this->load->language('api/update_order_status');

			$json = array();


		
$image_f=  file_get_contents('php://input');

			$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
			file_put_contents($nameZip, $image_f);
			

			
			$zipArc = zip_open($nameZip);
			
			if (is_resource($zipArc)) {
			
				$first_order_history = true;
				$sql_order_history = "INSERT INTO `".DB_PREFIX."order_history` (`order_id`,`order_status_id`,`date_added`) VALUES ";
				
				$first_order = true;
				$sql_order = "INSERT INTO `".DB_PREFIX."order` (`order_id`,`order_status_id`) VALUES ";
			

			  	while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			  			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			  			
			$order_id_array = json_decode($dump);
		
					
		

		
			foreach ($order_id_array as $parent) {
			
				$order_id = $parent->{'order_id'};
				$order_status_id = $parent->{'order_status_id'};				
			
				$sql_order_history .= ($first_order_history ) ? "" : ",";
				$sql_order_history .= " ( $order_id,$order_status_id, NOW()) ";
				$first_order_history = false;
				
				$sql_order .= ($first_order ) ? "" : ",";
				$sql_order .= " ( $order_id,$order_status_id) ";
				$first_order = false;
				 
			}
			}}
			
			if (!$first_order) {
			
				$sql_order .=" ON DUPLICATE KEY UPDATE  ";
				$sql_order .= "`order_status_id`= VALUES(`order_status_id`)";
				$sql_order .=";";
				$this->db->query($sql_order );
			}
			
			if (!$first_order_history ) {
			

				$sql_order_history .=";";
				$this->db->query($sql_order_history );
			}
			
			
			zip_close($zipArc);
			unlink($nameZip);
			$json['success'] = 'update_order_status complete' ;
		}else{
				$json['error']   = 'zip not archive' ;				
		}
			
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
	}
	public function get_order() {
		
		

		$this->load->language('api/get_order');
    $type_option  = $this->request->get['type_option'];


		$json = array();





		

			$param = (int)$this->request->post['param'];

			$param2 = $this->request->post['param2'];

			$param3 = $this->request->post['param3'];

		  $language_id= $this->getDefaultLanguageId();

		

			//$sql = "SELECT order_table.order_id, order_table.invoice_no, order_table.invoice_prefix, order_table.store_id, order_table.store_name, order_table.store_url, order_table.customer_id, order_table.customer_group_id, order_table.firstname, order_table.lastname, order_table.email, order_table.telephone, order_table.fax, order_table.custom_field, order_table.payment_firstname, order_table.payment_lastname, order_table.payment_company, order_table.payment_address_1, order_table.payment_address_2, order_table.payment_city, order_table.payment_postcode, order_table.payment_country, order_table.payment_country_id, order_table.payment_zone, order_table.payment_zone_id, order_table.payment_address_format, order_table.payment_custom_field, order_table.payment_method, order_table.payment_code, order_table.shipping_firstname, order_table.shipping_lastname, order_table.shipping_company, order_table.shipping_address_1, order_table.shipping_address_2, order_table.shipping_city, order_table.shipping_postcode, order_table.shipping_country, order_table.shipping_country_id, order_table.shipping_zone, order_table.shipping_zone_id, order_table.shipping_address_format, order_table.shipping_custom_field, order_table.shipping_method, order_table.shipping_code, order_table.comment, order_table.total, order_table.order_status_id, order_table.affiliate_id, order_table.commission, order_table.marketing_id , order_table.date_added, order_table.date_modified,order_product.product_id ,product_option_value_table.option_value_id,order_product.quantity,order_product.price,order_product.total,order_total_table.title as title_ship,	sum(IFNULL(order_total_table.value,0)) + sum(IFNULL(order_total_bbcod.value,0)) as value_ship,sum(customer_reward_table.points) FROM `".DB_PREFIX."order` as order_table ";
      $sql = "SELECT  customer_table.firstname as firstname_kl,customer_table.lastname as lastname_kl,customer_table.email as email_kl,customer_table.telephone as telephone_kl, order_table.order_id, order_table.invoice_no, order_table.invoice_prefix, order_table.store_id, order_table.store_name, order_table.store_url, order_table.customer_id, order_table.customer_group_id, order_table.firstname, order_table.lastname, order_table.email, order_table.telephone, order_table.fax, order_table.custom_field, order_table.payment_firstname, order_table.payment_lastname, order_table.payment_company, order_table.payment_address_1, order_table.payment_address_2, order_table.payment_city, order_table.payment_postcode, order_table.payment_country, order_table.payment_country_id, order_table.payment_zone, order_table.payment_zone_id, order_table.payment_address_format, order_table.payment_custom_field, order_table.payment_method, order_table.payment_code, order_table.shipping_firstname, order_table.shipping_lastname, order_table.shipping_company, order_table.shipping_address_1, order_table.shipping_address_2, order_table.shipping_city, order_table.shipping_postcode, order_table.shipping_country, order_table.shipping_country_id, order_table.shipping_zone, order_table.shipping_zone_id, order_table.shipping_address_format, order_table.shipping_custom_field, order_table.shipping_method, order_table.shipping_code, order_table.comment, order_table.total, order_table.order_status_id, order_table.affiliate_id, order_table.commission, order_table.marketing_id , order_table.date_added, order_table.date_modified,order_product.product_id ,product_option_value_table.option_value_id,order_product.quantity,order_product.price,order_product.total,order_total_table.title as title_ship,	sum(IFNULL(order_total_table.value,0)) + sum(IFNULL(order_total_bbcod.value,0)) as value_ship,sum(IFNULL(customer_reward_table.points,0)),currency_table.code as currency,currency_table.value as currencyvalue,SUM(IFNULL(order_total_coupon.value,0)) as coupon_value FROM `".DB_PREFIX."order` as order_table ";
			

			

			$sql .= " LEFT JOIN `".DB_PREFIX."order_product` as order_product on order_product.order_id = order_table.order_id ";

			$sql .= " LEFT JOIN `".DB_PREFIX."order_option` as order_option_table on order_option_table.order_id = order_product.order_id and order_option_table.order_product_id = order_product.order_product_id ";

			$sql .= " LEFT JOIN `".DB_PREFIX."product_option_value` as product_option_value_table on product_option_value_table.product_option_id = order_option_table.product_option_id and product_option_value_table.product_id= order_product.product_id and product_option_value_table.product_option_value_id = order_option_table.product_option_value_id"; //and product_option_value_table.product_option_value_id = order_option_table.product_option_value_id 

      if (!empty($type_option)) {
          //$sql .= " and product_option_value_table.option_id = $type_option";
          $sql .= " and product_option_value_table.option_id in($type_option)";
      }
			$sql .= " LEFT JOIN `".DB_PREFIX."customer_reward` as customer_reward_table on customer_reward_table.customer_id= order_table.customer_id";
      
      $sql .= " LEFT JOIN `".DB_PREFIX."currency` as currency_table on currency_table.currency_id= order_table.currency_id";
            
      $sql .= " LEFT JOIN `".DB_PREFIX."customer` as customer_table on customer_table.customer_id= order_table.customer_id and customer_table.language_id= $language_id ";
      
      $sql .= " LEFT JOIN `".DB_PREFIX."order_total` as order_total_table on order_total_table.order_id= order_table.order_id and order_total_table.code = 'shipping'";
      
      $sql .= " LEFT JOIN `".DB_PREFIX."order_total` as order_total_coupon on order_total_coupon.order_id= order_table.order_id and order_total_coupon.code = 'coupon'";

      $sql .= " LEFT JOIN `".DB_PREFIX."order_total` as order_total_bbcod on order_total_bbcod.order_id= order_table.order_id and (order_total_bbcod.code = 'bb_cod' or order_total_bbcod.code = 'cod_cdek_total')";

		

			if ($param === -100){

      

        if (empty($param2)){

        }

        else{

          $sql .= " WHERE DATE_FORMAT(order_table.date_added,'%Y-%m-%d') >= '$param2' and order_table.order_status_id <> 0";

        }

      

			}

			elseif ($param === -1){

      

        if (empty($param2)){

      

				    $sql .= " WHERE order_table.order_status_id <> 5  and order_table.order_status_id <> 0";

        }

        else{

            $sql .= " WHERE order_table.order_status_id <> 5 and DATE_FORMAT(order_table.date_added,'%Y-%m-%d') >= '$param2' and order_table.order_status_id <> 0";

        	

			  }

      }

			elseif($param === -200){



				$sql .= " WHERE  DATE_FORMAT(order_table.date_added,'%Y-%m-%d') >= '$param2' and DATE_FORMAT(order_table.date_added,'%Y-%m-%d') <= '$param3' and order_table.order_status_id <> 0";



      }  

			else{

				$sql .= " WHERE order_table.order_id = $param  and order_table.order_status_id <> 0";		

			};				

			//if (!empty($type_option)) {
       //  $sql .=" and product_option_value_table.option_value_id is not null"; 
      //};

			$sql .= " GROUP BY order_table.order_id, order_table.invoice_no, order_table.invoice_prefix, order_table.store_id, order_table.store_name, order_table.store_url, order_table.customer_id, order_table.customer_group_id, order_table.firstname, order_table.lastname, order_table.email, order_table.telephone, order_table.fax, order_table.custom_field, order_table.payment_firstname, order_table.payment_lastname, order_table.payment_company, order_table.payment_address_1, order_table.payment_address_2, order_table.payment_city, order_table.payment_postcode, order_table.payment_country, order_table.payment_country_id, order_table.payment_zone, order_table.payment_zone_id, order_table.payment_address_format, order_table.payment_custom_field, order_table.payment_method, order_table.payment_code, order_table.shipping_firstname, order_table.shipping_lastname, order_table.shipping_company, order_table.shipping_address_1, order_table.shipping_address_2, order_table.shipping_city, order_table.shipping_postcode, order_table.shipping_country, order_table.shipping_country_id, order_table.shipping_zone, order_table.shipping_zone_id, order_table.shipping_address_format, order_table.shipping_custom_field, order_table.shipping_method, order_table.shipping_code, order_table.comment, order_table.total, order_table.order_status_id, order_table.affiliate_id, order_table.commission, order_table.marketing_id , order_table.date_added, order_table.date_modified,order_product.product_id ,product_option_value_table.option_value_id,order_product.quantity,order_product.price,order_product.total,currency_table.code,currency_table.value ";
      $sql .= " ORDER BY order_table.order_id, order_product.order_product_id ";
		

		

			$query = $this->db->query( $sql );

	

			$json['success'] = $query->rows ;

			

			$this->response->addHeader('Content-Type: application/json');

			//$this->response->setCompression(9);

			$this->response->setOutput(json_encode($json));



		
		
	}
	
	
	
	
	
	public function deleteNoms() {
		
		$this->load->language('api/deleteNoms');

		$json = array();


		
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_attribute`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_description`;";
			$this->db->query( $sql );

			$sql = "DELETE FROM `".DB_PREFIX."seo_url` WHERE `query` LIKE 'product_id=%';\n";
			$this->db->query( $sql );

			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_discount`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_filter`;";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_image`;";
			$this->db->query( $sql );

			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_option`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_option_value`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_recurring`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_related`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_reward`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_special`";
			$this->db->query( $sql );
			
			//$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_category`";
			//$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_download`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_layout`";
			$this->db->query( $sql );
			
			$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_store`";
			$this->db->query( $sql );
			

			
				
			$json['success'] = 'noms udaleni' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	public function deleteCategories() {
		
		$this->load->language('api/deleteCategories');

		$json = array();


		
			$sql = "TRUNCATE TABLE `".DB_PREFIX."category`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_description`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_to_store`;";
			$this->db->query( $sql );

			$sql = "DELETE FROM `".DB_PREFIX."seo_url` WHERE `query` LIKE 'category_id=%';\n";
			$this->db->query( $sql );

			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_to_layout`;";
			$this->db->query( $sql );
			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_filter`;";
			$this->db->query( $sql );
			
			//$sql = "TRUNCATE TABLE `".DB_PREFIX."product_to_category`;";
			//$this->db->query( $sql );

			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_path`";
			$this->db->query( $sql );
			
				
			$json['success'] = 'Categories udaleni' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
		
	}
	
	public function category_continue() {
		$this->load->language('api/category_continue');

			$json = array();


		
			$pahts_array= json_decode(htmlspecialchars_decode($this->request->post['pahts']));
			$parents_array= json_decode(htmlspecialchars_decode($this->request->post['parents']));
		  $storeids_array= json_decode(htmlspecialchars_decode($this->request->post['storeids']));
      
			$first_path = true;
			$sql_path = "INSERT INTO `".DB_PREFIX."category_path` (`category_id`,`path_id`,`level`) VALUES ";		
		
			$first_category = true;
			$sql_category = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `parent_id`) VALUES ";
		
    
      $first_category_to_store = true;
			$sql_category_to_store = "INSERT INTO `".DB_PREFIX."category_to_store` (`category_id`,`store_id`) VALUES "; 
			
      $first_del_category_to_store = true;
			$sql_del_category_to_store = "DELETE FROM `".DB_PREFIX."category_to_store` WHERE category_id IN ("; 
    
      $category_ids = array();
    
    
    
			foreach ($pahts_array as $path) {
					
						
						
						$category_id= $path->{'category_id'};
						
						$paths = $this->object_to_array($path->{'path_arr'});
			
						foreach ($paths as $path_str) {
						
							$path_id = $path_str['path_id'];	
							$level= $path_str['level'];
						
							$sql_path .= ($first_path) ? "" : ",";
						
							$sql_path .= " ($category_id,$path_id,$level) ";
							$first_path = false;
						}	
			}
		
      $category_to_storeOption = array();
			foreach ($storeids_array as $parent) {
			
				$category_id= $parent->{'category_id'};
				$store_id = $parent->{'store_id'};
			
        if (!isset($category_ids[$category_id][$store_id]) ) {
      
				  $sql_category_to_store .= ($first_category_to_store ) ? "" : ",";
				  $sql_category_to_store .= " ( $category_id, $store_id ) ";
				  $first_category_to_store = false;
          
          $category_ids[$category_id][$store_id] = true;
          
        }else { 
        
          $category_ids[$category_id][$store_id] = true;
        }   
        
        if (!isset($category_to_storeOption[$category_id]) ) {
        
          $sql_del_category_to_store .= ($first_del_category_to_store ) ? "" : ",";
				  $sql_del_category_to_store .= "  $category_id  ";
          $first_del_category_to_store = false;
          
          $category_to_storeOption[$category_id] = $store_id;
            
        }
			}
    

			foreach ($parents_array as $parent) {
			
				$category_id= $parent->{'category_id'};
				$parent_id = $parent->{'parent_id'};
			
				$sql_category .= ($first_category ) ? "" : ",";
				$sql_category .= " ( $category_id, $parent_id ) ";
				$first_category = false; 
			}
			
      
      if (!$first_del_category_to_store ) {
	
				$sql_del_category_to_store .=");";
				$this->db->query($sql_del_category_to_store );
			}
      
      
      
      if (!$first_category_to_store ) {
	
				$sql_category_to_store .=";";
				$this->db->query($sql_category_to_store );
			}
      
      
			if (!$first_category ) {
			
				$sql_category .=" ON DUPLICATE KEY UPDATE  ";
				$sql_category .= "`parent_id`= VALUES(`parent_id`)";

				$sql_category .=";";
				$this->db->query($sql_category );
			}
			
			if (!$first_path ) {
			
				$sql_path .=" ON DUPLICATE KEY UPDATE  ";
				$sql_path .= "`level`= VALUES(`level`)";
				$sql_path .=";";
				$this->db->query($sql_path );
			}
			
			$json['success'] = 'Category continue' ;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		
	}
		
	public function category_add() {
		$this->load->language('api/category_add');

			$json = array();


			
			$vozvrat_json = array();
			
			$image_f=  file_get_contents('php://input');

			$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
			file_put_contents($nameZip, $image_f);
			

			
			$zipArc = zip_open($nameZip);
			
			if (is_resource($zipArc)) {
			
				$available_store_ids = $this->getAvailableStoreIds();
				$languages = $this->getLanguages();
				
				$url_alias_ids = array();
				//if (!$this->use_table_seo_url) {
						$url_alias_ids = $this->getCategorysSEOKeywords();
				//}
			
			  	$sql = "SHOW COLUMNS FROM `".DB_PREFIX."category_description` LIKE 'meta_title'";
				$query = $this->db->query( $sql );
			$exist_meta_title = ($query->num_rows > 0) ? true : false;
			
			$image_exist= (int)$this->request->get['img'];
			$seo_update= (int)$this->request->get['seo_update'];
			
			$first_category = true;
			$sql_category = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `image`, `parent_id`, `top`, `column`, `date_added`, `date_modified`, `status`) VALUES ";
			
			$first_category_description = true;
      
      $category_ids = array();
			
			if ($exist_meta_title) {
				$sql_category_description = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ";
				} else {
					$sql_category_description = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ";					
			}
			
			$first_category_to_store = true;
			$sql_category_to_store = "INSERT INTO `".DB_PREFIX."category_to_store` (`category_id`,`store_id`) VALUES "; 
			
			$first_category_to_layout = true;
			$sql_category_to_layout = "INSERT INTO `".DB_PREFIX."category_to_layout` (`category_id`,`store_id`,`layout_id`) VALUES ";
			
			$sql = "Select max(`category_id`) as `category_id` from `".DB_PREFIX."category`;";
			$result = $this->db->query( $sql );
			$category_id_max = 0;
			foreach ($result->rows as $row) {				
				$category_id_max = (int)$row['category_id'];				
			}

			$i = 1;
			
			$first_delete_path = true;
			$sql_first_delete_path = "DELETE FROM `".DB_PREFIX."category_path` WHERE category_id IN (";
			$sql_second_delete_path = "DELETE FROM `".DB_PREFIX."category_path` WHERE path_id IN (";
			
			$first_url_alias  = true;
			$first_url_aliasUPDATE  = true;

			$sql_url_alias =       "INSERT INTO `".DB_PREFIX."seo_url` (`keyword`,`query`,`store_id`,`language_id`) VALUES ";
			$sql_url_aliasUPDATE = "INSERT INTO `".DB_PREFIX."seo_url` (`seo_url_id`,`keyword`,`query`,`store_id`,`language_id`) VALUES ";
			
			while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			  			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

			
			$data_array= json_decode($dump);
			

			foreach ($data_array as $category){
				
										 
				$category_id = $category->{'category_id'};

			
				if ($category_id == 0) {
				
					$category_id_max = $category_id_max + 1;				
					$category_id  = $category_id_max ;
					$insert = 1;		
															
				}else {														
					$insert = 0;
					
					$sql_first_delete_path .= ($first_delete_path ) ? "" : ",";
					$sql_second_delete_path .= ($first_delete_path ) ? "" : ",";
					
					$sql_first_delete_path .= " $category_id ";
					$sql_second_delete_path .= " $category_id ";	
					$first_delete_path = false;
																						
				}; 
						
				
				//$names = $this->object_to_array($data->{'names'});
				// extract the category details
				
        $language_id_u= $category->{'language_id_u'};
				
				$image= $category->{'image'};
				$parent_id = $category->{'parent_id'};
				$top = $category->{'top'};
				
				$column = $category->{'column'};
				//$sort_order = $category->{'sort_order'};
				$date_added = $category->{'date_added'};
				$date_modified = $category->{'date_modified'};
				$names = $this->object_to_array($category->{'name'});
				$descriptions = $this->object_to_array($category->{'description'});
				if ($exist_meta_title) {
					$meta_titles = $this->object_to_array($category->{'meta_title'});
				}

				$meta_descriptions = $this->object_to_array($category->{'meta_description'});
				$meta_keywords = $this->object_to_array($category->{'meta_keyword'});
						
				$keywords = $this->object_to_array($category->{'seo_keyword'});	
				
				$store_ids = $this->object_to_array($category->{'store_ids'});
				$layout = $this->object_to_array($category->{'layout'});
				$status = $this->object_to_array($category->{'status'});
				
	
	
	
				// generate and execute SQL for inserting the category
				$sql_category .= ($first_category ) ? "" : ",";
				$sql_category .= " ( $category_id,   ";				
				$sql_category .= "'$image',";				
				$sql_category .= " $parent_id, $top, $column,'$date_added',";
				$sql_category .= "'$date_modified',";
				$sql_category .= " $status) ";

				$first_category = false;
				
				
				
				//$store_id = 0;
				
				
				foreach ($languages as $language) {
					$language_code = $language['code'];
					$language_id = $language['language_id'];
         
          if ($language_code !== $language_id_u) {
              continue;
          }	
          
          
					$name = isset($names[$language_code]) ? urldecode($this->db->escape($names[$language_code])) : '';
					$description = isset($descriptions[$language_code]) ? urldecode($this->db->escape($descriptions[$language_code])) : '';
					if ($exist_meta_title) {
						$meta_title = isset($meta_titles[$language_code]) ? urldecode($this->db->escape($meta_titles[$language_code])) : '';
					}
					$meta_description = isset($meta_descriptions[$language_code]) ? urldecode($this->db->escape($meta_descriptions[$language_code])) : '';
					$meta_keyword = isset($meta_keywords[$language_code]) ? urldecode($this->db->escape($meta_keywords[$language_code])) : '';
					if ($exist_meta_title) {
						$sql_category_description .= ($first_category_description ) ? "" : ",";		
						$sql_category_description .= " ( $category_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' ) ";
					} else {
						$sql_category_description .= ($first_category_description ) ? "" : ",";
						$sql_category_description .= " ( $category_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' ) ";
					}
					
					if(isset($keywords[$language_code]) and (($seo_update==1) or ($insert == 1))) {
						
							$keyword= isset($keywords[$language_code]) ? urldecode($this->db->escape($keywords[$language_code])) : '';
							
              foreach ($store_ids as $store_id) {
              									
							   if (isset($url_alias_ids[$category_id][$store_id][$language_id ])) {										
										
										$url_alias_id = $url_alias_ids[$category_id][$store_id][$language_id];
										
																
										$sql_url_aliasUPDATE .= ($first_url_aliasUPDATE  ) ? "" : ",";
										$sql_url_aliasUPDATE .= " ('$url_alias_id','$keyword','category_id=$category_id',$store_id,$language_id )";
										$first_url_aliasUPDATE  = false;
							   }else{
										$sql_url_alias .= ($first_url_alias  ) ? "" : ",";
										$sql_url_alias .= " ('$keyword','category_id=$category_id',$store_id,$language_id )";
										$first_url_alias  = false;
	               }
							}
					}
					
					
					
					
					$first_category_description = false;
				}
				
				foreach ($store_ids as $store_id) {
					//if (in_array((int)$store_id,$available_store_ids)) {
					if (!isset($category_ids[$category_id][$store_id]) ) {
          
						  $sql_category_to_store .= ($first_category_to_store ) ? "" : ",";
					
						  $sql_category_to_store .= " ($category_id,$store_id) ";
						  $first_category_to_store = false;
              
              $category_ids[$category_id][$store_id] = true;
              
          }else{
             
						  $category_ids[$category_id][$store_id] = true;
					}
				}
        
				$layouts = array();
				foreach ($layout as $layout_part) {
					$next_layout = explode(':',$layout_part);
					if ($next_layout===false) {
						$next_layout = array( 0, $layout_part );
					} else if (count($next_layout)==1) {
						$next_layout = array( 0, $layout_part );
					}
					if ( (count($next_layout)==2) && (in_array((int)$next_layout[0],$available_store_ids)) && (is_string($next_layout[1])) ) {
						$store_id = (int)$next_layout[0];
						$layout_name = $next_layout[1];
						if (isset($layout_ids[$layout_name])) {
							$layout_id = (int)$layout_ids[$layout_name];
							if (!isset($layouts[$store_id])) {
								$layouts[$store_id] = $layout_id;
							}
						}
					}
				}
				foreach ($layouts as $store_id => $layout_id) {
				
					$sql_category_to_layout .= ($first_category_to_layout) ? "" : ",";
				
					$sql_category_to_layout .= " ($category_id,$store_id,$layout_id) ";
					$first_category_to_layout = false;
				}
			
				$vozvrat_json[$category->{'ref'}]= $category_id;
			

				$i++;
		
		
			}//перебор переданного массива
			
			
			if (!$first_delete_path) {
			
				$sql_first_delete_path .=");";
				$this->db->query($sql_first_delete_path );
			
			
				$sql_second_delete_path .=");";
				//$this->db->query($sql_second_delete_path);
			}
			
						
			if (!$first_category ) {
			
				$sql_category .=" ON DUPLICATE KEY UPDATE  ";
				if ($image_exist==1) {
					//$sql_category .= "`image`= VALUES(`image`),";
				}	
				$sql_category .= "`parent_id`= VALUES(`parent_id`),";
				//$sql_category .= "`top`= VALUES(`top`),";
				//$sql_category .= "`column`= VALUES(`column`),";
				//$sql_category .= "`sort_order`= VALUES(`sort_order`),";
				//$sql_category .= "`date_added`= VALUES(`date_added`),";
				$sql_category .= "`date_modified`= VALUES(`date_modified`)";
				//$sql_category .= "`status`= VALUES(`status`)";
				$sql_category .=";";
				$this->db->query($sql_category );
			}
			
			
			
			
			
			if (!$first_category_description ) {
			
				if ($exist_meta_title) {
			
					$sql_category_description .=" ON DUPLICATE KEY UPDATE  ";


					$sql_category_description .= "`name`= VALUES(`name`)";
					//$sql_category_description .= ",`description`= VALUES(`description`)";
					
					//if ($seo_update==1) {
					//	$sql_category_description .= ",`meta_title`= VALUES(`meta_title`)";
					//	$sql_category_description .= ",`meta_description`= VALUES(`meta_description`)";
					//	$sql_category_description .= ",`meta_keyword`= VALUES(`meta_keyword`)";
					//}
	
					$sql_category_description .=";";
					$this->db->query($sql_category_description );
				} else {
				
					$sql_category_description .=" ON DUPLICATE KEY UPDATE  ";


					$sql_category_description .= "`name`= VALUES(`name`)";
					//$sql_category_description .= ",`description`= VALUES(`description`)";
					
					//if ($seo_update==1) {
					//	$sql_category_description .= ",`meta_description`= VALUES(`meta_description`)";
					//	$sql_category_description .= ",`meta_keyword`= VALUES(`meta_keyword`)";
					//}
	
					$sql_category_description .=";";
					$this->db->query($sql_category_description );
				
				}
			}
			
			}}
			
			if (!$first_url_aliasUPDATE ) {								
			
				$sql_url_aliasUPDATE .=" ON DUPLICATE KEY UPDATE  ";
				$sql_url_aliasUPDATE .= "`keyword`= VALUES(`keyword`)";
												
				$sql_url_aliasUPDATE .=";";
				$this->db->query($sql_url_aliasUPDATE );
			}
			 
			if (!$first_url_alias) {								
			
				$sql_url_alias .=";";
				$this->db->query($sql_url_alias );
			}
			
			if (!$first_category_to_store ) {
			
				//$sql_category_to_store .=" ON DUPLICATE KEY UPDATE  ";

				//$sql_category_to_store .= "`store_id`= VALUES(`store_id`)";
	
				$sql_category_to_store .=";";
				$this->db->query($sql_category_to_store );
			}
			if (!$first_category_to_layout ) {
			
				$sql_category_to_layout .=" ON DUPLICATE KEY UPDATE  ";

				$sql_category_to_layout .= "`layout_id`= VALUES(`layout_id`)";
	
				$sql_category_to_layout .=";";
				$this->db->query($sql_category_to_layout );
			}
			
			
			
			$json['success'] = $vozvrat_json ;
						
			zip_close($zipArc);
			unlink($nameZip);
			
			}else{
				$json['error'] = 'zip error option add' ;
			}
															
		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function add() {       
	
		$this->load->language('api/product');

    $type_option  = $this->request->get['type_option'];

		$json = array();
    
    $option_vid = array();


			
			$vozvrat_json = array();			
			
			//$product_structure= '{"foo-bar": 12345}';
			//$obj = json_decode($product_structure);
			
	$image_f=  file_get_contents('php://input');

	$nameZip = DIR_CACHE . $this->request->get['nameZip'].'.zip';
			
	file_put_contents($nameZip, $image_f);
			

			
	$zipArc = zip_open($nameZip);
			
	if (is_resource($zipArc)) {
			
$languages = $this->getLanguages();
			
			// get list of the field names, some are only available for certain OpenCart versions
			$query = $this->db->query( "DESCRIBE `".DB_PREFIX."product`" );
			$product_fields = array();
			foreach ($query->rows as $row) {
					$product_fields[] = $row['Field'];
			}
			
			// Opencart versions from 2.0 onwards also have product_description.meta_title
			$sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_description` LIKE 'meta_title'";
			$query = $this->db->query( $sql );
			$exist_meta_title = ($query->num_rows > 0) ? true : false;
			
			// Opencart versions from 2.0 onwards also have product_description.meta_title
			$sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_description` LIKE 'meta_h1'";
			$query = $this->db->query( $sql );
			$exist_meta_h1 = ($query->num_rows > 0) ? true : false;
			
			// some older versions of OpenCart use the 'product_tag' table
			$exist_table_product_tag = false;
			$query = $this->db->query( "SHOW TABLES LIKE '".DB_PREFIX."product_tag'" );
			$exist_table_product_tag = ($query->num_rows > 0);
			
			// get pre-defined store_ids
			$available_store_ids = $this->getAvailableStoreIds();
			
			// get pre-defined layouts
			$layout_ids = $this->getLayoutIds();
			
			// find existing manufacturers, only newly specified manufacturers will be added
			$manufacturers = $this->getManufacturers();
			
			// get weight classes
			$weight_class_ids = $this->getWeightClassIds();
			
			// get length classes
			$length_class_ids = $this->getLengthClassIds();
					
			// save old url_alias_ids
			$url_alias_ids = array();
			//if (!$this->use_table_seo_url) {
					$url_alias_ids = $this->getProductSEOKeywords();
			//}
			
      
      
			
			$sql = "Select max(`product_id`) as `product_id` from `".DB_PREFIX."product`;";
			$result = $this->db->query( $sql );
			$product_id_max = 0;
			foreach ($result->rows as $row) {				
				$product_id_max  = (int)$row['product_id'];				
			}
			
			
			// generate and execute SQL for inserting the product
			$sql_product  = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`sku`,`upc`,";
				
			$sql_product  .= in_array('ean',$product_fields) ? "`ean`," : "";
			$sql_product  .= in_array('jan',$product_fields) ? "`jan`," : "";
			$sql_product  .= in_array('isbn',$product_fields) ? "`isbn`," : "";
			$sql_product  .= in_array('mpn',$product_fields) ? "`mpn`," : "";
      

      

      
			//$sql_product  .= "`location`,`image`,`stock_status_id`,`model`,`manufacturer_id`,`shipping`,`price`,`points`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
			$sql_product  .= "`location`,`stock_status_id`,`model`,`manufacturer_id`,`shipping`,`price`,`points`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";


			$sql_product  .= "`tax_class_id`,`length`,`width`,`height`,`length_class_id`,`subtract`,`minimum`) VALUES ";
			
			//код обновления
			$sql_productDUPLICATE  =" ON DUPLICATE KEY UPDATE  ";
			

			$first_product_to_layout = true;
			$sql_product_to_layout = "INSERT INTO `".DB_PREFIX."product_to_layout` (`product_id`,`store_id`,`layout_id`) VALUES ";
			
			$first_product_related = true;
			$sql_product_related = "INSERT INTO `".DB_PREFIX."product_related` (`product_id`,`related_id`) VALUES ";
			
			$firstsql_product_to_store  = true;
			$sql_product_to_store = "INSERT INTO `".DB_PREFIX."product_to_store` (`product_id`,`store_id`) VALUES ";

			$first_url_alias  = true;
			$first_url_aliasUPDATE  = true;

			$sql_url_alias =       "INSERT INTO `".DB_PREFIX."seo_url` (`keyword`,`query`,`store_id`,`language_id`) VALUES ";
			$sql_url_aliasUPDATE = "INSERT INTO `".DB_PREFIX."seo_url` (`seo_url_id`,`keyword`,`query`,`store_id`,`language_id`) VALUES "; 
			

			//$first_category_id = true; 
			//$sql_category_id = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
			
			$first_product_attribute = true; 
			$sql_product_attribute = "INSERT INTO `".DB_PREFIX."product_attribute` (`product_id`,`attribute_id`,`language_id`,`text`) VALUES ";
			
			$first_product_filter = true; 
			$sql_product_filter = "INSERT INTO `".DB_PREFIX."product_filter` (`product_id`,`filter_id`) VALUES ";
			
			
			$first_product_option = true; 
			$sql_product_option = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`,`product_id`,`option_id`,`value`,`required`) VALUES ";			
			
			$first_product_option_value = true;
      $first_product_option_value0 = true; 
			$sql_product_option_value = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_value_id`,`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix`) VALUES ";
			$sql_product_option_value0 = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix`) VALUES ";
      
			$first_del_product_option_value= true;
			$sql_del_product_option_value = "DELETE FROM `".DB_PREFIX."product_option_value` WHERE product_id IN (";
			
			$first_del_product_attribute= true;
			$sql_del_product_attribute = "DELETE FROM `".DB_PREFIX."product_attribute` WHERE product_id IN (";
			
			$first_del_product_filter= true;
			$sql_del_product_filter = "DELETE FROM `".DB_PREFIX."product_filter` WHERE product_id IN (";

			
      
      $first_manufacturer_to_store = true;
		  $sql_first_delete_manufacturer_to_store = "DELETE FROM `".DB_PREFIX."manufacturer_to_store` WHERE manufacturer_id IN (";
   
      $first_delete_manufacturer_to_store  = true;
   
      $sqlmanufacturer_to_store = "INSERT INTO `".DB_PREFIX."manufacturer_to_store` (`manufacturer_id`,`store_id` ) VALUES ";
    


      
      
			$seo_update   = (int)$this->request->get['seo_update'];
      $description_update   = (int)$this->request->get['description_update'];
									

			$option_id= 0;

			
			$sql = "Select max(`product_option_id`) as `product_option_id` from `".DB_PREFIX."product_option`;";
			$result = $this->db->query( $sql );
			$product_option_id= 0;
			
			foreach ($result->rows as $row) {				
				$product_option_id= (int)$row['product_option_id'];
				$product_option_id++;				
			}

			$ProductOption= $this->getProductOption();
      $ProductOptionValue= $this->getProductOptionValue();

			$first_delete_product_option = true;
			$sql_delete_product_option = "DELETE FROM `".DB_PREFIX."product_option` WHERE product_id IN (";


			$first_product_description = true;
			$first_product_tag  = true;
			
			if ($exist_table_product_tag) {
						if ($exist_meta_title) {
							$sql_product_description  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description` , '$meta_title', '$meta_description', '$meta_keyword', '$tag')" ;
							
						} else {
							$sql_product_description  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`" ;
						}
						if ($exist_meta_h1) {
							$sql_product_description  .= ", `meta_h1`";
						}
						$sql_product_description  .= ") VALUES ";
						
						$sql_product_tag  = "INSERT INTO `".DB_PREFIX."product_tag` (`product_id`,`language_id`,`tag`) VALUES " ;
					} else {
						if ($exist_meta_title) {
							$sql_product_description  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`, `tag`" ;
						} else {
							$sql_product_description  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `tag`";
					}
					if ($exist_meta_h1) {
							$sql_product_description  .= ", `meta_h1`";
					}
					$sql_product_description  .= ") VALUES ";
	}

				
			//$first_delete_path = true;
			//$sql_first_delete_path = "DELETE FROM `".DB_PREFIX."product_to_category` WHERE product_id IN (";
			

				
			$sql_delete_product_special= "DELETE FROM `".DB_PREFIX."product_special` WHERE product_id IN ("; 
			$first_delete_product_special= true;

			$sql_product_special  = "INSERT INTO `".DB_PREFIX."product_special` (`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end`) VALUES "; 		
											
			$first_product_special = true;
			
			$sql_delete_product_discount= "DELETE FROM `".DB_PREFIX."product_discount` WHERE product_id IN ("; 
			$first_delete_product_discount= true;

			$sql_product_discount  = "INSERT INTO `".DB_PREFIX."product_discount` (`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end`) VALUES "; 		
											
			$first_product_discount = true;
			
      $manufacturer_ids = array();
      
			$i = 1;
			  	
		while ($zip_entry = zip_read($zipArc)) {
			  		if (zip_entry_open($zipArc, $zip_entry, "r")) {
			  			
			$dump = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			
			
		
			$data_array= json_decode($dump);
			error_log($dump);
			
			foreach ($data_array as $data){
				
										 
				$product_id = $data->{'product_id'};
			
				if ($product_id == 0) {
				
					$product_id_max = $product_id_max + 1;				
					$product_id = $product_id_max;
					$insert = 1;		
															
				}else {														
					$insert = 0;
					
					/* $sql_first_delete_path .= ($first_delete_path ) ? "" : ",";
					
					
					$sql_first_delete_path .= " $product_id "; */
					
					//$first_delete_path = false;
																						
				}; 
						
				
				
				
				$categories = $data->{'categories'};
				
				$options = $data->{'options'};

        
				
				$attributes= $data->{'attributes'};
				$filters= $data->{'filters'};
				$attributes_udal= $data->{'attributes_udal'};
				$filters_udal= $data->{'filters_udal'};
				
							
				$quantity = $data->{'quantity'};
				$model = urldecode($this->db->escape($data->{'model'}));
				$manufacturer_name = urldecode($this->db->escape($data->{'manufacturer_name'}));
        $manufacturer_image = urldecode($this->db->escape($data->{'manufacturer_image'}));
        $manufacturer_description = urldecode($this->db->escape($data->{'manufacturer_description'}));
				$keyword_manufacturer= urldecode($this->db->escape($data->{'keyword_manufacturer'}));
				$language_id_u= $data->{'language_id_u'};
				
				//$image = urldecode($this->db->escape($data->{'image'}));
				$shipping = $data->{'shipping'};
				$price = trim($data->{'price'});
				$points = $data->{'points'};
				$date_added = $data->{'date_added'};
				$date_modified = $data->{'date_modified'};
				$date_available = $data->{'date_available'};
				$weight = (float)$data->{'weight'};
				$weight_unit = $data->{'weight_unit'};
				$status = $data->{'status'};
				$tax_class_id = $data->{'tax_class_id'};
				$stock_status_id = $data->{'stock_status_id'};
				
				$descriptions = $this->object_to_array($data->{'descriptions'});
					
				$meta_titles = $this->object_to_array($data->{'meta_titles'});

				$meta_h1s   = $this->object_to_array($data->{'meta_h1'});
				
				$meta_descriptions = $this->object_to_array($data->{'meta_descriptions'});
				
				$names = $this->object_to_array($data->{'names'});
				
				$meta_keywords = $this->object_to_array($data->{'meta_keywords'});
				$tags = $this->object_to_array($data->{'tags'});
				
				$keywords = $this->object_to_array($data->{'seo_keyword'});
				
				$length = (float)$data->{'length'};
				$width = (float)$data->{'width'};
				$height = (float)$data->{'height'};
	
				//$sort_order = $data->{'sort_order'};
	
				$measurement_unit = $data->{'measurement_unit'};
				$sku = urldecode($this->db->escape($data->{'sku'}));
				$upc = urldecode($this->db->escape($data->{'upc'}));
	
				$ean = urldecode($this->db->escape($data->{'ean'}));
	
	
				$jan = urldecode($this->db->escape($data->{'jan'}));
	
	
				$isbn = urldecode($this->db->escape($data->{'isbn'}));
	
				$mpn =  urldecode($this->db->escape($data->{'mpn'}));
        
        
        
        
        

	
				$location = urldecode($this->db->escape($data->{'location'}));			
				
				$store_ids = $data->{'store_ids'};
	
				$related_ids = $data->{'related_ids'};
	
				$layout = $data->{'layout'};
				$subtract = $data->{'subtract'};
				$minimum = $data->{'minimum'};
				
				
				if (empty($data->{'specials'})) {
					$specials_empty = true;
				}else{ 
					$specials_empty = false;
					$specials = $data->{'specials'};
				};
				
				if (empty($data->{'akcii'})) {
					$akcii_empty = true;
				}else{ 
					$akcii_empty = false;
					$akcii = $data->{'akcii'};
				};
				
				//$vozvrat_json[$product['ref']]= $this->storeProductIntoDatabase( $product, $languages, $product_fields, $exist_table_product_tag, $exist_meta_title//, //$layout_ids, $available_store_ids, $manufacturers, $weight_class_ids, $length_class_ids, $url_alias_ids );
				
				// extract the product details										
				$weight_class_id = (isset($weight_class_ids[$weight_unit])) ? $weight_class_ids[$weight_unit] : 0;
				

				
				//if (!$this->use_table_seo_url) {
					//$keyword = $this->db->escape($seo_keyword);
				//}

				$length_class_id = (isset($length_class_ids[$measurement_unit])) ? $length_class_ids[$measurement_unit] : 0;

				
				if ($manufacturer_name) {        
					$this->storeManufacturerIntoDatabase( $manufacturers, $manufacturer_name, $store_ids, $available_store_ids,$keyword_manufacturer,$languages,$language_id_u,$manufacturer_image,$manufacturer_description );
					$manufacturer_id = $manufacturers[$manufacturer_name]['manufacturer_id'];
				} else {
					$manufacturer_id = 0;
				}
						
				if ($i==1) {} else {$sql_product  .=",";};
				
				$sql_product  .= "($product_id,$quantity,'$sku','$upc',";
				$sql_product  .= in_array('ean',$product_fields) ? "'$ean'," : "";
				$sql_product  .= in_array('jan',$product_fields) ? "'$jan'," : "";
				$sql_product  .= in_array('isbn',$product_fields) ? "'$isbn'," : "";
				$sql_product  .= in_array('mpn',$product_fields) ? "'$mpn'," : "";
				
        

        
 
        //$sql_product  .= "'$location','$image',$stock_status_id,'$model',$manufacturer_id,$shipping,$price,$points,";
		$sql_product  .= "'$location',$stock_status_id,'$model',$manufacturer_id,$shipping,$price,$points,";
				$sql_product  .= "'$date_added',";
				$sql_product  .= "'$date_modified',";
				$sql_product  .= "'$date_available',";
				$sql_product  .= "$weight,$weight_class_id,$status,";
				$sql_product  .= "$tax_class_id,$length,$width,$height,'$length_class_id','$subtract','$minimum')";
		
		
		

				//$store_id = 0;
				
				foreach ($languages as $language) {
					$language_code = $language['code'];
					$language_id = $language['language_id'];					
					
          if ($language_code !== $language_id_u) {
              continue;
          }	
          
          
					$name = isset($names[$language_code]) ? urldecode($this->db->escape($names[$language_code])) : '';
					$description = isset($descriptions[$language_code]) ? urldecode($this->db->escape($descriptions[$language_code])) : '';
					if ($exist_meta_title) {
						$meta_title = isset($meta_titles[$language_code]) ? urldecode($this->db->escape($meta_titles[$language_code])) : '';
					}
					if ($exist_meta_h1) {
							$meta_h1 = isset($meta_h1s[$language_code]) ? urldecode($this->db->escape($meta_h1s[$language_code])) : '';
					}
					$meta_description = isset($meta_descriptions[$language_code]) ? urldecode($this->db->escape($meta_descriptions[$language_code])) : '';
					$meta_keyword = isset($meta_keywords[$language_code]) ? urldecode($this->db->escape($meta_keywords[$language_code])) : '';
					$tag = isset($tags[$language_code]) ? urldecode($this->db->escape($tags[$language_code])) : '';
					
					
					if ($exist_table_product_tag) {
					
						$sql_product_description  .= ($first_product_description ) ? "" : ",";
						if ($exist_meta_title) {

							$sql_product_description  .= " ( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword'";

						} else {
							
							$sql_product_description  .= " ( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword'";								

						}
						if ($exist_meta_h1) {
							$sql_product_description  .= ", '$meta_h1'";
						}
						$sql_product_description  .= ")";
						
						if (($seo_update == 1) or ($insert== 1)) {
							$sql_product_tag  .= ($first_product_tag  ) ? "" : ",";
							$sql_product_tag  .= " ($product_id, $language_id, '$tag') ";

							$first_product_tag  = false;
						}
					} else {
					
						$sql_product_description  .= ($first_product_description ) ? "" : ",";
						if ($exist_meta_title) {
							
							$sql_product_description  .= " ( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword', '$tag' ";							


						} else {
							
							$sql_product_description  .= " ( $product_id, $language_id, '$name', '$description',  '$meta_description', '$meta_keyword', '$tag' " ;

	
						}
						if ($exist_meta_h1) {
							$sql_product_description  .= ", '$meta_h1'";
						}
						$sql_product_description  .= ")";

						if(isset($keywords[$language_code]) and (($seo_update == 1) or ($insert== 1))){
						
							$keyword= isset($keywords[$language_code]) ? urldecode($this->db->escape($keywords[$language_code])) : '';
							
              
              foreach ($store_ids as $store_id) {
              									
							   if (isset($url_alias_ids[$product_id][$store_id][$language_id ])) {										
										
										$url_alias_id = $url_alias_ids[$product_id][$store_id][$language_id];
										
																
										$sql_url_aliasUPDATE .= ($first_url_aliasUPDATE  ) ? "" : ",";
										$sql_url_aliasUPDATE .= " ('$url_alias_id','$keyword','product_id=$product_id',$store_id,$language_id )";
										$first_url_aliasUPDATE  = false;
							   }else{
										$sql_url_alias .= ($first_url_alias  ) ? "" : ",";
										$sql_url_alias .= " ('$keyword','product_id=$product_id',$store_id,$language_id )";
										$first_url_alias  = false;
	
							   }
              
              }
						}


					}
					$first_product_description = false;
					
		 			foreach ($attributes as $attribute_str) {
					
							$attribute_id = $attribute_str->{'attribute_id'};
							
							
							$text= urldecode($this->db->escape($attribute_str->{'text'}));	
					
							$sql_product_attribute .= ($first_product_attribute ) ? "" : ",";
							$first_product_attribute = false;
							$sql_product_attribute .= " ($product_id,$attribute_id,$language_id,'$text') ";
					}
					
	
					
				}
				foreach ($filters as $filter_str) {
					
							$filter_id= $filter_str->{'filter_id'};

							$sql_product_filter .= ($first_product_filter ) ? "" : ",";
							$first_product_filter = false;
							$sql_product_filter .= " ($product_id,$filter_id) ";
				}
					
				$countcategories = count($categories);	
				
				/* if ($countcategories > 0) {

					$count_main_category= 1;
					
					foreach ($categories as $category_id) {
					
						$sql_category_id .= ($first_category_id ) ? "" : ",";
						
						//$main_category = ($count_main_category == $countcategories ) ? 1 : 0;
            $main_category  =  $category_id->{'main'};
						$category_id_id =  $category_id->{'id'};
            
            
						$sql_category_id .= " ($product_id,$category_id_id) ";

						$first_category_id = false;
	
						
						$count_main_category= $count_main_category + 1;
					}

				} */
				//if (!$this->use_table_seo_url) {
				//	if ($keyword) {					
					
						//foreach ($store_ids as $store_id) {
						//	if (in_array((int)$store_id,$available_store_ids)) {
							
								//$store_id = 0;
								
								//foreach ($keywords as $keyword_struct ) {
									
									//$language_id = $keywords['language_id'];
									
									
								//}	
							//}
					//	}			
				//	}
				//}
				
        
        if (count($store_ids) > 0) {
       
       
          if (!isset($manufacturer_ids[$manufacturer_id]) ) {
       
            $sql_first_delete_manufacturer_to_store .= ($first_delete_manufacturer_to_store ) ? "" : ",";
		        $sql_first_delete_manufacturer_to_store .= " $manufacturer_id ";
            $first_delete_manufacturer_to_store = false;
            
          }
        }
        
        
				foreach ($store_ids as $store_id) {
					//if (in_array((int)$store_id,$available_store_ids)) {
						
						$sql_product_to_store .= ($firstsql_product_to_store  ) ? "" : ",";
						$sql_product_to_store .= " ($product_id,$store_id)";
						$firstsql_product_to_store  = false;
            
            if (!isset($manufacturer_ids[$manufacturer_id][$store_id]) ) {
            
              $sqlmanufacturer_to_store .= ($first_manufacturer_to_store ) ? "" : ",";
		          $sqlmanufacturer_to_store .= " ( $manufacturer_id, $store_id) ";
            
              $first_manufacturer_to_store = false;
              
              $manufacturer_ids[$manufacturer_id][$store_id] = true;
              
            }else{
            
              $manufacturer_ids[$manufacturer_id][$store_id] = true;
              
            }
            
            

					//}
				}
        
        
        
				$layouts = array();
				foreach ($layout as $layout_part) {
					$next_layout = explode(':',$layout_part);
					if ($next_layout===false) {
						$next_layout = array( 0, $layout_part );
					} else if (count($next_layout)==1) {
						$next_layout = array( 0, $layout_part );
					}
					if ( (count($next_layout)==2) && (in_array((int)$next_layout[0],$available_store_ids)) && (is_string($next_layout[1])) ) {
						$store_id = (int)$next_layout[0];
						$layout_name = $next_layout[1];
						if (isset($layout_ids[$layout_name])) {
							$layout_id = (int)$layout_ids[$layout_name];
							if (!isset($layouts[$store_id])) {
								$layouts[$store_id] = $layout_id;
							}
						}
					}
				}

				foreach ($layouts as $store_id => $layout_id) {
					$sql_product_to_layout .= ($first_product_to_layout ) ? "" : ",";
					$sql_product_to_layout .= " ($product_id,$store_id,$layout_id)";
					$first_product_to_layout = false;
				}
				if (count($related_ids) > 0) {
					
					foreach ($related_ids as $related_id) {
						$sql_product_related .= ($first_product_related ) ? "" : ",";
						$first_product_related = false;
						$sql_product_related .= "($product_id,$related_id)";
					}
				}
				
				if (count($attributes_udal) > 0) {
				//foreach ($attributes_udal as $attribute_id) {			
							
					$sql_del_product_attribute .= ($first_del_product_attribute) ? "" : ",";
					$first_del_product_attribute= false;
					$sql_del_product_attribute .= " $product_id ";
				}
				
				if (count($filters_udal) > 0) {
				//foreach ($attributes_udal as $attribute_id) {			
							
					$sql_del_product_filter .= ($first_del_product_filter) ? "" : ",";
					$first_del_product_filter= false;
					$sql_del_product_filter .= " $product_id ";
				}

	
				
				if (!empty($type_option)) {
					
					if (count($options) == 0) {
					
					
            $sql_del_product_option_value .= ($first_del_product_option_value) ? "" : ",";
					
					  $sql_del_product_option_value .= " $product_id ";
					
					  $first_del_product_option_value= false;
            
            
            $sql_delete_product_option .= ($first_delete_product_option ) ? "" : ",";
						$sql_delete_product_option .= " $product_id ";
						$first_delete_product_option = false;
            
					
					} else {

						foreach ($options as $option) {
			
               $option_id= $option->{'option_id'};
               
               if (isset($ProductOption[$product_id][$option_id]) ) {
               
                 $option_vid[$product_id][$option_id] = $ProductOption[$product_id][$option_id];
               
               }
               
               if ((!isset($ProductOption[$product_id][$option_id])) and (!isset($option_vid[$product_id][$option_id]))) {
						

            
    							$sql_product_option .= ($first_product_option ) ? "" : ","; 
    							$sql_product_option .= " ($product_option_id,$product_id,$option_id,'',1) ";
    							$first_product_option = false;
    							
    							$product_option_id_t = $product_option_id;
    							
    							$product_option_id++;	
                  
                  $option_vid[$product_id][$option_id] = $product_option_id_t;
                  					
						  } else {
						
							   $product_option_id_t = $option_vid[$product_id][$option_id];
							
						  }
      
      
      
							$option_value_id= $option->{'option_value_id'};
							$quantity= $option->{'quantity'};
							$subtract= $option->{'subtract'};
							$price= $option->{'price'};
							$price_prefix= $option->{'price_prefix'};
							$points= $option->{'points'};
							$points_prefix= $option->{'points_prefix'};
							$weight= $option->{'weight'};
							$weight_prefix= $option->{'weight_prefix'};
              
		
							if (isset($ProductOptionValue[$product_id][$option_id][$option_value_id])) {
                
                
                    $product_option_value_id = $ProductOptionValue[$product_id][$option_id][$option_value_id];
                    
                    $sql_product_option_value .= ($first_product_option_value ) ? "" : ",";
                    $sql_product_option_value .= "  ($product_option_value_id,$product_option_id_t, $product_id, $option_id, $option_value_id ,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix', $weight,'$weight_prefix') ";
							      $first_product_option_value = false;
                
                
              }else{
              
                    $sql_product_option_value0 .= ($first_product_option_value0 ) ? "" : ",";

							      $sql_product_option_value0 .= "  ($product_option_id_t, $product_id, $option_id, $option_value_id ,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix', $weight,'$weight_prefix') ";
                    $first_product_option_value0 = false;
              
              }
						}
					}
						
					
					
				}
				
        $sql_delete_product_special .= ($first_delete_product_special ) ? "" : ",";
				$sql_delete_product_special .= " $product_id ";
				$first_delete_product_special= false;
        
        
        if (!$specials_empty) {
				
					

					
					if (count($specials) == 0) {
					} else {
						foreach ($specials as $special) {
			
							$customer_group_id= $special->{'customer_group_id'};
							$priority= $special->{'priority'};
							$date_start= $special->{'date_start'};
							$price= $special->{'price'};
							$date_end= $special->{'date_end'};

		
							$sql_product_special .= ($first_product_special ) ? "" : ",";
					 
							$sql_product_special .= "  ($product_id, $customer_group_id,  $priority, $price,'$date_start','$date_end') ";
							
							

							$first_product_special = false;
						}
					}
				}
				
        $sql_delete_product_discount .= ($first_delete_product_discount ) ? "" : ",";
				$sql_delete_product_discount .= " $product_id ";
				$first_delete_product_discount= false;
        
        
        
				if (!$akcii_empty) {
				
					

					
					if (count($akcii) == 0) {
					} else {
						foreach ($akcii as $special) {
			
							$customer_group_id= $special->{'customer_group_id'};
							$priority= $special->{'priority'};
							$date_start= $special->{'date_start'};
							$price= $special->{'price'};
							$date_end= $special->{'date_end'};
							$quantity= $special->{'quantity'};

		
							$sql_product_discount .= ($first_product_discount ) ? "" : ",";
					 
							$sql_product_discount .= "  ($product_id, $customer_group_id, $quantity,  $priority, $price,'$date_start','$date_end') ";
							
							

							$first_product_discount = false;
						}
					}
				}
				
				$vozvrat_json[$data->{'ref'}]= $product_id;
			

				$i++;
			
			}//перебор переданного массива	
			}//if				
			}//while

				//код обновления
				$sql_productDUPLICATE   .= "`quantity`= VALUES(`quantity`),";
				$sql_productDUPLICATE   .= "`sku`= VALUES(`sku`),";
				$sql_productDUPLICATE   .= "`upc`= VALUES(`upc`),";
								
				$sql_productDUPLICATE   .= in_array('ean',$product_fields) ? "`ean` = VALUES(`ean`)," : "";
				$sql_productDUPLICATE   .= in_array('jan',$product_fields) ? "`jan` = VALUES(`jan`)," : "";
				$sql_productDUPLICATE   .= in_array('isbn',$product_fields) ? "`isbn` = VALUES(`isbn`)," : "";
				$sql_productDUPLICATE   .= in_array('mpn',$product_fields) ? "`mpn` = VALUES(`mpn`)," : "";
				

        
        
				
				$sql_productDUPLICATE   .= "`location`= VALUES(`location`),";
				$sql_productDUPLICATE   .= "`stock_status_id`= VALUES(`stock_status_id`),";
				$sql_productDUPLICATE   .= "`model`= VALUES(`model`),";
				$sql_productDUPLICATE   .= "`manufacturer_id`= VALUES(`manufacturer_id`),";
				//$sql_productDUPLICATE   .= "`image`= VALUES(`image`),";
				$sql_productDUPLICATE   .= "`shipping`= VALUES(`shipping`),";
				$sql_productDUPLICATE   .= "`price`= VALUES(`price`),";
				$sql_productDUPLICATE   .= "`points`= VALUES(`points`),";

				//$sql_productDUPLICATE   .= "`date_added`= '$date_added',";
				$sql_productDUPLICATE   .= "`date_modified`= '$date_modified',";
				$sql_productDUPLICATE   .= "`date_available`= '$date_available',";

				$sql_productDUPLICATE   .= "`weight`= VALUES(`weight`),";
				$sql_productDUPLICATE   .= "`weight_class_id`= VALUES(`weight_class_id`),";
				$sql_productDUPLICATE   .= "`status`= VALUES(`status`),";
				
				$sql_productDUPLICATE   .= "`tax_class_id`= VALUES(`tax_class_id`),";

				$sql_productDUPLICATE   .= "`length`= VALUES(`length`),";
				$sql_productDUPLICATE   .= "`width`= VALUES(`width`),";
				$sql_productDUPLICATE   .= "`height`= VALUES(`height`),";
				$sql_productDUPLICATE   .= "`length_class_id`= VALUES(`length_class_id`),";
				//$sql_productDUPLICATE   .= "`sort_order`= VALUES(`sort_order`),";
				$sql_productDUPLICATE   .= "`subtract`= VALUES(`subtract`),";
				$sql_productDUPLICATE   .= "`minimum`= VALUES(`minimum`)";


				$sql_product  .=$sql_productDUPLICATE;
			
			$sql_product  .=";";				
			$this->db->query($sql_product);
			
			if (!$first_delete_product_special ) {
			
				$sql_delete_product_special .=");";
				$this->db->query($sql_delete_product_special );
				
				$sql = "Select max(`product_special_id`) as `product_special_id` from `".DB_PREFIX."product_special`;";
				$result = $this->db->query( $sql );
				$product_special_id= 0;
				
				foreach ($result->rows as $row) {				
					$product_special_id= (int)$row['product_special_id'];
					$product_special_id++;
									
				}
				$sql = "ALTER TABLE `".DB_PREFIX."product_special` AUTO_INCREMENT = $product_special_id ;";
				$this->db->query($sql);
			}
			if (!$first_delete_product_discount ) {
			
				$sql_delete_product_discount .=");";
				$this->db->query($sql_delete_product_discount );
				
				$sql = "Select max(`product_discount_id`) as `product_discount_id` from `".DB_PREFIX."product_discount`;";
				$result = $this->db->query( $sql );
				$product_discount_id= 0;
				
				foreach ($result->rows as $row) {				
					$product_discount_id= (int)$row['product_discount_id'];
					$product_discount_id++;
									
				}
				$sql = "ALTER TABLE `".DB_PREFIX."product_discount` AUTO_INCREMENT = $product_discount_id;";
				$this->db->query($sql);
			}
			
			/* if (!$first_delete_path) {
			
				$sql_first_delete_path .=");";
				$this->db->query($sql_first_delete_path );
			} */
			
			if (!$first_delete_product_option) {
			
				$sql_delete_product_option .=");";
				$this->db->query($sql_delete_product_option );
			}
			
			if (!$first_del_product_attribute) {
			
				$sql_del_product_attribute .=");";
				$this->db->query($sql_del_product_attribute );
			}
			if (!$first_del_product_filter) {
			
				$sql_del_product_filter .=");";
				$this->db->query($sql_del_product_filter );
			}
			if (!$first_product_attribute) {
			
				$sql_product_attribute .=";";
				$this->db->query($sql_product_attribute);
			}
			if (!$first_product_filter) {
			
				$sql_product_filter .=";";
				$this->db->query($sql_product_filter);
			}
						
			if (!$first_product_option ) {
			
				$sql_product_option .=";";
				$this->db->query($sql_product_option );
			}
			

			
			if (!$first_del_product_option_value) {
			
				$sql_del_product_option_value .=");";
				$this->db->query($sql_del_product_option_value );
			}
			
			if (!$first_product_option_value ) {
				
				$sqlproduct_option_value_id = "Select max(`product_option_value_id`) as `product_option_value_id` from `".DB_PREFIX."product_option_value`;";
				$resultproduct_option_value = $this->db->query( $sqlproduct_option_value_id );
				$product_option_idproduct_option_value= 1;
			
				foreach ($resultproduct_option_value ->rows as $row) {				
					$product_option_idproduct_option_value= (int)$row['product_option_value_id'];
					$product_option_idproduct_option_value++;				
				}
				$sqlproduct_option_value_id = "ALTER TABLE `".DB_PREFIX."product_option_value` AUTO_INCREMENT=$product_option_idproduct_option_value;";
				$this->db->query( $sqlproduct_option_value_id );
			
      
      
        $sql_product_option_value .=" ON DUPLICATE KEY UPDATE  ";
				$sql_product_option_value .= "`quantity`= VALUES(`quantity`),";
				$sql_product_option_value .= "`subtract`= VALUES(`subtract`),";
				$sql_product_option_value .= "`price`= VALUES(`price`),";
				$sql_product_option_value .= "`price_prefix`= VALUES(`price_prefix`),";
				$sql_product_option_value .= "`points`= VALUES(`points`),";
				$sql_product_option_value .= "`points_prefix`= VALUES(`points_prefix`),";
				$sql_product_option_value .= "`weight`= VALUES(`weight`),";				
				$sql_product_option_value .= "`weight_prefix`= VALUES(`weight_prefix`)";
				$sql_product_option_value .=";";
				$this->db->query($sql_product_option_value );
			}
      
      if (!$first_product_option_value0 ) {
				
				$sql_product_option_value0 .=";";
				$this->db->query($sql_product_option_value0 );
			}
      
      if (!$first_delete_manufacturer_to_store) {
      
        $sql_first_delete_manufacturer_to_store .=");";
        $this->db->query($sql_first_delete_manufacturer_to_store );
      }

     if (!$first_manufacturer_to_store) {								
			
				$sqlmanufacturer_to_store .=";";
				$this->db->query($sqlmanufacturer_to_store );
		 }
      
      
							
			if (!$first_product_special ) {				
							
				$sql_product_special .=";";
				$this->db->query($sql_product_special );				
			}
			if (!$first_product_discount ) {				
							
				$sql_product_discount .=";";
				$this->db->query($sql_product_discount );				
			}
			
			if (!$first_product_description ) {
			
				$sql_product_descriptionDUPLICATE =" ON DUPLICATE KEY UPDATE  ";
			
			
				if ($exist_table_product_tag) {
					
				
						if ($exist_meta_title) {

								$sql_product_descriptionDUPLICATE.= "`name`= VALUES(`name`)";
								if ($description_update == 1) {
								  $sql_product_descriptionDUPLICATE.= ",`description`= VALUES(`description`)";
                }
								if (($seo_update == 1) or ($insert== 1)) {
								
								
									$sql_product_descriptionDUPLICATE.= ",`meta_title`= VALUES(`meta_title`),";
									$sql_product_descriptionDUPLICATE.= "`meta_description`= VALUES(`meta_description`),";
									$sql_product_descriptionDUPLICATE.= "`meta_keyword`= VALUES(`meta_keyword`)";
									
							}
						} else {
																						
								$sql_product_descriptionDUPLICATE.= "`name`= VALUES(`name`)";
								if ($description_update == 1) {
								  $sql_product_descriptionDUPLICATE.= ",`description`= VALUES(`description`)";
                }
								if (($seo_update == 1) or ($insert== 1)) {
								
								$sql_product_descriptionDUPLICATE.= ",`meta_description`= VALUES(`meta_description`),";
								$sql_product_descriptionDUPLICATE.= "`meta_keyword`= VALUES(`meta_keyword`)";								
							}
						}
										
						
					} else {
					

						if ($exist_meta_title) {														
							
								$sql_product_descriptionDUPLICATE.= "`name`= VALUES(`name`)";
								if ($description_update == 1) {
								  $sql_product_descriptionDUPLICATE.= ",`description`= VALUES(`description`)";
                }
								if (($seo_update == 1) or ($insert== 1)) {
								
								$sql_product_descriptionDUPLICATE.= ",`meta_title`= VALUES(`meta_title`),";
								$sql_product_descriptionDUPLICATE.= "`meta_description`= VALUES(`meta_description`),";
								$sql_product_descriptionDUPLICATE.= "`meta_keyword`= VALUES(`meta_keyword`),";
								$sql_product_descriptionDUPLICATE.= "`tag`= VALUES(`tag`)";
														
}

						} else {														
							
								$sql_product_descriptionDUPLICATE.= "`name`= VALUES(`name`)";
								if ($description_update == 1) {
								  $sql_product_descriptionDUPLICATE.= ",`description`= VALUES(`description`)";
                }
								if (($seo_update == 1) or ($insert== 1)) {
								$sql_product_descriptionDUPLICATE.= ",`meta_description`= VALUES(`meta_description`),";
								$sql_product_descriptionDUPLICATE.= "`meta_keyword`= VALUES(`meta_keyword`),";
								$sql_product_descriptionDUPLICATE.= "`tag`= VALUES(`tag`)";
								}
						}


				}	
			
				if ($exist_meta_h1) {
					if (($seo_update == 1) or ($insert== 1)) {
						$sql_product_descriptionDUPLICATE.= ",`meta_h1`= VALUES(`meta_h1`)";
					}
				}
			
			
				$sql_product_description  .=$sql_product_descriptionDUPLICATE;
			

				$sql_product_description  .=";";
				$this->db->query($sql_product_description  );
			}
			
			if (!$first_product_tag  ) {
				
					$sql_product_tag  .=" ON DUPLICATE KEY UPDATE  ";
					$sql_product_tag  .= "`tag`= VALUES(`tag`)";
					
					$sql_product_tag  .=";";
					$this->db->query($sql_product_tag  );
				
			}
			
			
			
			/* if (!$first_category_id) {
				$sql_category_id .=";";
				$this->db->query($sql_category_id );
			} */
			
			if (!$first_url_aliasUPDATE  ) {								
			
				$sql_url_aliasUPDATE .=" ON DUPLICATE KEY UPDATE  ";
				$sql_url_aliasUPDATE .= "`keyword`= VALUES(`keyword`)";
												
				$sql_url_aliasUPDATE .=";";
				$this->db->query($sql_url_aliasUPDATE );
			}
			 
			if (!$first_url_alias  ) {								
			
				$sql_url_alias .=";";
				$this->db->query($sql_url_alias );
			}
			
			if (!$firstsql_product_to_store  ) {
				
				$sql_product_to_store .=" ON DUPLICATE KEY UPDATE  ";
				$sql_product_to_store .= "`store_id`= VALUES(`store_id`)";								
				$sql_product_to_store .=";";
				$this->db->query($sql_product_to_store );
			}
			
			if (!$first_product_to_layout ) {
			
				$sql_product_to_layout .=";";
				$this->db->query($sql_product_to_layout );
			}
			
			
			if (!$first_product_related) {
			
				$sql_product_related .=";";
				$this->db->query($sql_product_related );				
			}

			$sql = "TRUNCATE TABLE `".DB_PREFIX."category_filter`;";
			$this->db->query( $sql );
			
			$sql = "Select product_to_category.category_id,product_filter.filter_id from `".DB_PREFIX."product_to_category` as product_to_category inner join `".DB_PREFIX."product_filter` as product_filter on product_filter.product_id = product_to_category .product_id GROUP BY product_to_category.category_id,product_filter.filter_id;";
			
			$result = $this->db->query( $sql );

			$first_category_filter = true;
			
			$sql_category_filter = "INSERT INTO `".DB_PREFIX."category_filter` (`category_id`,`filter_id`) VALUES ";
			
			foreach ($result->rows as $row) {
							
				$category_id= (int)$row['category_id'];
				$filter_id  = (int)$row['filter_id'];


		
				$sql_category_filter .= ($first_category_filter ) ? "" : ",";
					 
				$sql_category_filter .= "  ($category_id, $filter_id ) ";
				$first_category_filter = false;				
			}
			if (!$first_category_filter ) {
			
				$sql_category_filter .=";";
				$this->db->query($sql_category_filter );				
			}

			//$json['success'] = $data->{'product_id'};
//			$product = array();
			//$product['product_id'] = $data->{'product_id'};
			//$json['success'] = $product['product_id'];
			
			
			
			
			
			
			//$vozvrat_json["id".$product['sku']]= 25; 			
			$json['success'] = $vozvrat_json ;
			//$json['success'] = $this->use_table_;			
			//$json['success'] = $product['descriptions'][$languages[0]['language_code']];			
			//$json['success'] = $obj ->{'foo-bar'};			
			//$json['success'] = urldecode($this->request->post['products']);

						
			zip_close($zipArc);
			unlink($nameZip);
				
			}													
		

		$this->response->addHeader('Content-Type: application/json');    
		//$this->response->setCompression(9);
		$this->response->setOutput(json_encode($json));
	}
	
	protected function object_to_array($data)				
	{
    			if (is_array($data) || is_object($data))
    			{
        			$result = array();
        			foreach ($data as $key => $value)
        			{
            						$result[$key] = $this->object_to_array($value);
        			}
       		 		return $result;
   			 }
    		return $data;
	}

	protected function getLanguages() {
		$query = $this->db->query( "SELECT * FROM `".DB_PREFIX."language` WHERE `status`=1 ORDER BY `code`" );
		return $query->rows;
	}
	
	protected function getAvailableStoreIds() {
		$sql = "SELECT store_id FROM `".DB_PREFIX."store`;";
		$result = $this->db->query( $sql );
		$store_ids = array(0);
		foreach ($result->rows as $row) {
			if (!in_array((int)$row['store_id'],$store_ids)) {
				$store_ids[] = (int)$row['store_id'];
			}
		}
		return $store_ids;
	}
	
	protected function getLayoutIds() {
		$result = $this->db->query( "SELECT * FROM `".DB_PREFIX."layout`" );
		$layout_ids = array();
		foreach ($result->rows as $row) {
			$layout_ids[$row['name']] = $row['layout_id'];
		}
		return $layout_ids;
	}
	
	protected function getManufacturers() {
		// find all manufacturers already stored in the database
		$manufacturer_ids = array();
		$sql  = "SELECT ms.manufacturer_id, ms.store_id, m.`name` FROM `".DB_PREFIX."manufacturer_to_store` ms ";
		$sql .= "INNER JOIN `".DB_PREFIX."manufacturer` m ON m.manufacturer_id=ms.manufacturer_id";
		$result = $this->db->query( $sql );
		$manufacturers = array();
		foreach ($result->rows as $row) {
			$manufacturer_id = $row['manufacturer_id'];
			$store_id = $row['store_id'];
			$name = $row['name'];
			if (!isset($manufacturers[$name])) {
				$manufacturers[$name] = array();
			}
			if (!isset($manufacturers[$name]['manufacturer_id'])) {
				$manufacturers[$name]['manufacturer_id'] = $manufacturer_id;
			}
			if (!isset($manufacturers[$name]['store_ids'])) {
				$manufacturers[$name]['store_ids'] = array();
			}
			if (!in_array($store_id,$manufacturers[$name]['store_ids'])) {
				$manufacturers[$name]['store_ids'][] = $store_id;
			}
		}
		return $manufacturers;
	}
	
	protected function getWeightClassIds() {
		// find the default language id
		$language_id = $this->getDefaultLanguageId();
		
		// find all weight classes already stored in the database
		$weight_class_ids = array();
		$sql = "SELECT `weight_class_id`, `unit` FROM `".DB_PREFIX."weight_class_description` WHERE `language_id`=$language_id;";
		$result = $this->db->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$weight_class_id = $row['weight_class_id'];
				$unit = $row['unit'];
				if (!isset($weight_class_ids[$unit])) {
					$weight_class_ids[$unit] = $weight_class_id;
				}
			}
		}

		return $weight_class_ids;
	}
		
	protected function getLengthClassIds() {
		// find the default language id
		$language_id = $this->getDefaultLanguageId();
		
		// find all length classes already stored in the database
		$length_class_ids = array();
		$sql = "SELECT `length_class_id`, `unit` FROM `".DB_PREFIX."length_class_description` WHERE `language_id`=$language_id;";
		$result = $this->db->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$length_class_id = $row['length_class_id'];
				$unit = $row['unit'];
				if (!isset($length_class_ids[$unit])) {
					$length_class_ids[$unit] = $length_class_id;
				}
			}
		}

		return $length_class_ids;
	}
	protected function getProductUrlAliasIds() {
		$sql  = "SELECT url_alias_id, SUBSTRING( query, CHAR_LENGTH('product_id=')+1 ) AS product_id ";
		$sql .= "FROM `".DB_PREFIX."url_alias` ";
		$sql .= "WHERE query LIKE 'product_id=%'";
		$query = $this->db->query( $sql );
		$url_alias_ids = array();
		foreach ($query->rows as $row) {
			$url_alias_id = $row['url_alias_id'];
			$product_id = $row['product_id'];
			$url_alias_ids[$product_id] = $url_alias_id;
		}
		return $url_alias_ids;
	}
	protected function getDefaultLanguageId() {
		$code = $this->config->get('config_language');
		$sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = '$code'";
		$result = $this->db->query( $sql );
		$language_id = 1;
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$language_id = $row['language_id'];
				break;
			}
		}
		return $language_id;
	}

	
	protected function getCategorysSEOKeywords() {
		$sql  = "SELECT * FROM `".DB_PREFIX."seo_url` ";
		$sql .= "WHERE query LIKE 'category_id=%' ";
		$query = $this->db->query( $sql );
		$seo_keywords = array();
		foreach ($query->rows as $row) {
			$category_id= (int)substr($row['query'],12);
			$store_id = $row['store_id'];
			$language_id = $row['language_id'];			
			$url_alias_id = $row['seo_url_id'];
			
			$seo_keywords[$category_id][$store_id][$language_id] = $url_alias_id;
		}
		
		return $seo_keywords;
	}
	protected function getProductSEOKeywords() {
		$sql  = "SELECT * FROM `".DB_PREFIX."seo_url` ";
		$sql .= "WHERE query LIKE 'product_id=%' ";
		$query = $this->db->query( $sql );
		$seo_keywords = array();
		foreach ($query->rows as $row) {
			$product_id= (int)substr($row['query'],11);
			$store_id = $row['store_id'];
			$language_id = $row['language_id'];			
			$url_alias_id = $row['seo_url_id'];
			
			$seo_keywords[$product_id][$store_id][$language_id] = $url_alias_id;
		}
		
		return $seo_keywords;
	}
  
	protected function getProductOption() {
		$sql  = "SELECT * FROM `".DB_PREFIX."product_option` ";
		$query = $this->db->query( $sql );
		$seo_keywords = array();
		foreach ($query->rows as $row) {
			$product_id= $row['product_id'];
			$option_id= $row['option_id'];
      
			$product_option_id= $row['product_option_id'];
			
			$seo_keywords[$product_id][$option_id] = $product_option_id;
		}
		
		return $seo_keywords;
	}	
	
	protected function getProductOptionValue() {

		$sql  = "SELECT * FROM `".DB_PREFIX."product_option_value` ";

		$query = $this->db->query( $sql );

		$seo_keywords = array();

		foreach ($query->rows as $row) {

			$product_id= $row['product_id'];
      $option_id= $row['option_id'];

      $product_option_value_id= $row['product_option_value_id'];

      			

			$option_value_id= $row['option_value_id'];

			

			$seo_keywords[$product_id][$option_id][$option_value_id] = $product_option_value_id;

		}

		

		return $seo_keywords;

	}
	
	  
  function getStoreIds() {
		$sql = "SELECT store_id,name FROM `".DB_PREFIX."store`;";
		$result = $this->db->query( $sql );
    
    $json = array();
    
    $json['success'] = $result ;
    
    
    $this->response->addHeader('Content-Type: application/json');
    
		$this->response->setOutput(json_encode($json));;
    
	}
	
	
	protected function storeManufacturerIntoDatabase( &$manufacturers, $name, &$store_ids, &$available_store_ids,$keyword_manufacturer, &$language_ids, $language_id_u,&$manufacturer_image,&$manufacturer_description ) {
		foreach ($store_ids as $store_id) {
			//if (!in_array( $store_id, $available_store_ids )) {
			//	continue;
			//}
      
			$name_name = $name;
      
			if (!isset($manufacturers[$name]['manufacturer_id'])) {
				
				$this->db->query("INSERT INTO ".DB_PREFIX."manufacturer SET name = '".$name."', image='$manufacturer_image', sort_order = '0'");
				$manufacturer_id = $this->db->getLastId();
				if (!isset($manufacturers[$name])) {
					$manufacturers[$name] = array();
				}
				
				//$manufacturers[$name]['manufacturer_id'] = $manufacturer_id;
        
        $manufacturer_description_table = false;
        $exist_name = false;
        $exist_meta_h1 = false;
        
        //$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = DATABASE() AND table_name  = '".DB_PREFIX."manufacturer_description';";
  				//$result = $this->db->query($query);
  			 
  			  //$sql= "SHOW COLUMNS FROM `".DB_PREFIX."manufacturer_description` LIKE 'name'";
  				//$query = $this->db->query( $sql );
  				//$exist_name = ($query->num_rows > 0) ? true : false;
  				
  				//$sql= "SHOW COLUMNS FROM `".DB_PREFIX."manufacturer_description` LIKE 'meta_h1'";
  				//$query = $this->db->query( $sql );
  				//$exist_meta_h1 = ($query->num_rows > 0) ? true : false;
  			 
  
  				//if (count($result->rows)==1) { 
     			//		$manufacturer_description = true;
  			  //	}
  
        
        foreach ($language_ids as $language) {
  					
  					$language_code = $language['code'];
  					$language_id = $language['language_id'];
  				
  					if ($language_code == $language_id_u) {
  				
  						$this->db->query("INSERT INTO `".DB_PREFIX."seo_url` (`query`,`keyword`, `store_id`,`language_id`) VALUES ( 'manufacturer_id=$manufacturer_id','$keyword_manufacturer',$store_id,$language_id)");
  						if ($manufacturer_description_table) {
  							
  							$sql= "INSERT INTO `".DB_PREFIX."manufacturer_description` (`language_id`,`manufacturer_id`,`description`,`meta_description`,`meta_keyword`,`meta_title`";
  							
  						if ($exist_name) {	
  							$sql.=",`name` ";
  						}
  						if ($exist_meta_h1) {	
  							$sql.=",`meta_h1` ";
  						}
  							
  						$sql.=") VALUES ($language_id, $manufacturer_id, '$manufacturer_description','$name_name','$name_name','$name_name' ";
  						
  						if ($exist_name) {	
  							$sql.=",'$name_name' ";
  						}
  						if ($exist_meta_h1) {	
  							$sql.=",'$name_name' ";
  						}
  						
  						$sql.=")";
  							
  						$this->db->query($sql);
  						
  						}	
  					}
            
            
            $manufacturers[$name]['manufacturer_id'] = $manufacturer_id;
  			}
        

                
      }else{
      
         $manufacturer_id = $manufacturers[$name]['manufacturer_id'];
         $this->db->query("INSERT INTO ".DB_PREFIX."manufacturer  (`manufacturer_id`,`image`)  VALUES ($manufacturer_id,'$manufacturer_image')  ON DUPLICATE KEY UPDATE `image`= VALUES(`image`); ");
      
      }
      

            
			//if (!isset($manufacturers[$name]['store_ids'])) {
			//	$manufacturers[$name]['store_ids'] = array();
			//}
			//if (!in_array($store_id,$manufacturers[$name]['store_ids'])) {
				$manufacturer_id = $manufacturers[$name]['manufacturer_id'];
				//$sql = "INSERT INTO `".DB_PREFIX."manufacturer_to_store` SET manufacturer_id='".(int)$manufacturer_id."', store_id='".(int)$store_id."'";
				//$this->db->query( $sql );
				$manufacturers[$name]['store_ids'][] = $store_id;
			//}
		}
	}


}