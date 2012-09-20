<?php


class DBQuery extends DBManager{
    
      public function __construct()
                        
                {
                    
		$this->checkConnection();
                                
                }
                              
         /**
	 * Ejecuta una sentencia SQL personalizada
	 * @param string $table, $campo, $condition
	 * @return boolean|mysqli_result
	 * @throws QueryException
	 */
                
     public function query_personalize($table,$campo,$condition) 
                {
                   
         $query_person=$this->query("select $campo from $table where $condition");
         
         return $query_person;
                
         
                }
                
                
    
  
    
    
}
//fin del archivo